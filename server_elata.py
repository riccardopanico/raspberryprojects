#!/usr/bin/env python3
# Server Flask per gestire le API
from flask import Flask, request, jsonify
import cx_Oracle
import traceback
import requests
import json
import datetime

# Funzioni di print colorate
badprint = False
def rprint(skk): print(" E "+skk)   if badprint else print("\033[91m E\033[00m {}" .format(skk))
def gprint(skk): print(" * "+skk)   if badprint else print("\033[92m *\033[00m {}" .format(skk))
def xprint(skk): print(":: "+skk) if badprint else print(":: \033[96m{}\033[00m" .format(skk))
app = Flask(__name__)

def salva_ip(ip_address):

    def is_ip_address_present(ip_address):
        with open('/home/stduser/driver/lista_ip.txt', 'r') as file:
            for line in file:
                if line.strip() == ip_address:
                    return True
        return False

    if not is_ip_address_present(ip_address):
        gprint("Trovato nuovo dispositivo: "+str(ip_address))
        with open('/home/stduser/driver/lista_ip.txt', 'a') as file:
            file.write(ip_address + '\n')

@app.route('/api/getTipiGuasto', methods=['POST'])
def getTipiGuasto():
    salva_ip(request.remote_addr)
    data = request.get_json()
    ID_MACCHINA = data.get('ID_MACCHINA')

    query = '''SELECT
        H.ID AS ID_MACCHINA_SIRIO,
        H.ID_MACCHINA,
        H.IP_ASSEGNATO,
        G.CODICE AS CODICE_GUASTO,
        G.DESCR AS DESCRIZIONE_GUASTO
    FROM
        TESEO_HARDWARE H,
        TESEO_TAB_GUASTI G
    WHERE
        H.ID_MACCHINA IS NOT NULL
        AND H.IP_ASSEGNATO IS NOT NULL
        AND H.CATEGORIA = G.CATEGORIA
        AND H.ID_MACCHINA = :ID_MACCHINA
    ORDER BY
        H.ID ASC
    '''

    dsn_tns = cx_Oracle.makedsn('130.61.84.61', '1521', service_name='php.nivadb.nivavcn.oraclevcn.com')
    con = cx_Oracle.connect(user='sirio_raspberry', password='sirio_raspberry', dsn=dsn_tns)
    cursor = con.cursor()
    print("Connesso al DB!")

    try:
        cursor.execute(query, {'ID_MACCHINA': ID_MACCHINA})
        rows = [dict(zip([column[0] for column in cursor.description], row)) for row in cursor.fetchall()]
        # rows = cursor.fetchall()
        return jsonify(tuple(rows)), 200
    except Exception as e:
        print("Si è verificato un errore: "+str(e))
        traceback.print_exc()
        return jsonify({'Errore': str(e)}), 400

@app.route('/api/get_fase_ga2', methods=['POST'])
def get_fase_ga2():
    gprint("Ricevuta richiesta get_fase_ga2")
    salva_ip(request.remote_addr)
    data = request.get_json() # Ottenere i dati inviati nella richiesta
    if 'codpref' not in data or 'nlotto' not in data: # Verificare se sono presenti tutti i campi richiesti
        rprint("Richiesta malformata:\n"+str(data))
        return jsonify({'Errore': 'Campi mancanti'}), 400
    else: # Parametri specificati correttamente
        codpref = int(data['codpref'])
        nlotto = int(data['nlotto'])
        query = """
            SELECT S.CODFASE, AF.DESCR AS DESCR_FASE, F.CODLAVOR, TO_CHAR(F.DATA, 'YYYY-MM-DD HH24:MI:SS') AS DATA
            FROM FASE_LOTTO_DETT F, SEQ_FASI_LAV S, PROD_LOT_TEST L, GRUPPI_MERC G, ARTICOLI A, ANAG_FASI_PROD AF, ORDCLI_PREFISSI OP
            WHERE L.CODART = A.CODART AND A.CODGM = G.CODGM AND G.CODLAV = S.CODLAV AND F.CODFASE = S.CODFASE
            --- AND F.SE_CL = 'S'
            AND OP.CODPREF = :CODPREF AND F.PREFISSO = OP.PREFISSO AND F.NLOTTO = :NLOTTO AND AF.CODFASE = S.CODFASE AND F.CODFASE IN ('TC', 'T1')
            GROUP BY F.DATA, S.CODFASE, F.CODLAVOR, AF.DESCR
            ORDER BY F.DATA, S.CODFASE, F.CODLAVOR
        """
        try:
            dsn_tns = cx_Oracle.makedsn("130.61.84.61", "1521", service_name="ga2web.nivadb.nivavcn.oraclevcn.com")
            con = cx_Oracle.connect(user='elata', password='elata', dsn=dsn_tns)
            gprint("Connesso al DB (ga2)")
            cursor = con.cursor()

            cursor.execute(query, [codpref, nlotto])
            rows = [dict(zip([column[0] for column in cursor.description], row)) for row in cursor.fetchall()]

            return jsonify(tuple(rows)), 200
        except Exception as e:
            rprint("Si è verificato un errore: "+str(e))
            traceback.print_exc()
            return jsonify({'Errore': str(e)}), 400

@app.route('/api/get_situazione_ga2', methods=['POST'])
def get_situazione_ga2():
    gprint("Ricevuta richiesta get_situazione_ga2")
    salva_ip(request.remote_addr)
    data = request.get_json()  # Ottenere i dati inviati nella richiesta
    if 'codpref' not in data or 'nlotto' not in data:  # Verificare se sono presenti tutti i campi richiesti
        rprint("Richiesta malformata:\n" + str(data))
        return jsonify({'Errore': 'Campi mancanti'}), 400
    else:  # Parametri specificati correttamente
        codice = int(data['codice'])
        codpref = int(data['codpref'])
        nlotto = int(data['nlotto'])

        # queryMateriale = """
        #     SELECT oce.codart as codmat, a.codgui,
        #     FROM prod_lot_test   plt, PROD_LOT_RIG    plr, ordcli_prefissi odf,
        #     ordcli_elem   oce, articoli a WHERE
        #     plt.prefisso = odf.prefisso
        #     and plt.prefisso = plr.prefisso
        #     and plt.nlotto = plr.nlotto
        #     AND plt.prefisso = odf.prefisso
        #     and plr.prefisso = oce.prefisso
        #     and plr.nordcli = oce.nordcli
        #     and plr.nrigocl = oce.nrigocl
        #     and a.codart = oce.codart
        #     AND odf.codpref = :codpref
        #     AND plt.nlotto = :nlotto
        #     and oce.id in (:codice)
        # """
        # queryPellami = f"""
        #     SELECT A.CODART,F_CALC_GIACENZA(A.CODART) QTSIT, F_SIT_ORF(A.CODART) QTORF FROM ARTICOLI A WHERE CODART = :materiale
        # """
        # queryTaglie = f"""
        #     SELECT OP.PREFISSO, :nlotto as nlotto, GR.CODMIS, A.CODART, F_CALC_GIACENZA_QT(A.CODART, GR.CODGUI, GR.CODMIS) QTSIT,
        #     F_QT_SIT_ORF(A.CODART, GR.CODGUI, GR.CODMIS) QTORF
        #     FROM GUIDE_RIGHE GR, ARTICOLI A, ORDCLI_PREFISSI OP WHERE
        #     OP.CODPREF = :codpref AND A.CODGUI = GR.CODGUI AND A.CODART = :materiale
        #     ORDER BY GR.CODMIS
        # """

        queryMateriale = """
            SELECT  OCE.CODART AS CODMAT, A.CODGUI,
            NVL((F_CONST_RIGLOT(ODF.PREFISSO, PLT.NLOTTO, OCE.ID)*F_QTORD_LOTTO (ODF.PREFISSO, PLT.NLOTTO)), 0) CONSUMO_LOTTO
            FROM PROD_LOT_TEST PLT, PROD_LOT_RIG PLR, ORDCLI_PREFISSI ODF,
            ORDCLI_ELEM OCE, ARTICOLI A WHERE
            PLT.PREFISSO = ODF.PREFISSO
            AND PLT.PREFISSO = PLR.PREFISSO
            AND PLT.NLOTTO = PLR.NLOTTO
            AND PLT.PREFISSO = ODF.PREFISSO
            AND PLR.PREFISSO = OCE.PREFISSO
            AND PLR.NORDCLI = OCE.NORDCLI
            AND PLR.NRIGOCL = OCE.NRIGOCL
            AND A.CODART = OCE.CODART
            AND ODF.CODPREF = :codpref
            AND PLT.NLOTTO = :nlotto
            AND OCE.ID IN (:codice)
        """
        queryPellami = f"""
            SELECT
            A.CODART,
            NVL(F_CALC_GIACENZA(A.CODART), 0) QTSIT,
            NVL(F_SIT_ORF(A.CODART), 0) QTORF,
            :consumo_lotto as CONSUMO_LOTTO,
            CASE WHEN F_CALC_GIACENZA(A.CODART) < :consumo_lotto THEN 1 ELSE 0 END as CONSUMO_MINORE_QTSIT
            FROM ARTICOLI A WHERE CODART = :materiale
        """
        queryTaglie = f"""
            SELECT OP.PREFISSO, :nlotto AS NLOTTO, GR.CODMIS, A.CODART,
                NVL(F_CALC_GIACENZA_QT(A.CODART, GR.CODGUI, GR.CODMIS), 0)  QTSIT,
                NVL(F_QTORD_CODMIS_LOTTO (PLR.PREFISSO,     PLR.NLOTTO, GR.CODGUI, GR.CODMIS), 0) QTORF,
                NVL(F_CONST_RIGLOT(PLR.PREFISSO, PLR.NLOTTO, 12)*F_QTORD_CODMIS_LOTTO (PLR.PREFISSO, PLR.NLOTTO, GR.CODGUI, GR.CODMIS), 0) CONSUMO_LOTTO,
                CASE
                    WHEN
                        NVL(F_CALC_GIACENZA_QT(A.CODART, GR.CODGUI, GR.CODMIS), 0)
                        <
                        NVL(F_CONST_RIGLOT(PLR.PREFISSO, PLR.NLOTTO, 12)*F_QTORD_CODMIS_LOTTO (PLR.PREFISSO, PLR.NLOTTO, GR.CODGUI, GR.CODMIS), 0)
                    THEN 1
                    ELSE 0
                END as CONSUMO_MINORE_QTSIT
            FROM
                GUIDE_RIGHE GR,
                ARTICOLI A,
                ORDCLI_PREFISSI OP,
                PROD_LOT_RIG PLR
            WHERE
                A.CODGUI = GR.CODGUI
                AND PLR.PREFISSO = OP.PREFISSO
                AND PLR.NLOTTO = :nlotto
                AND OP.CODPREF = :codpref
                AND A.CODART = :materiale
            ORDER BY GR.CODMIS
        """
        try:
            dsn_tns = cx_Oracle.makedsn("130.61.84.61", "1521", service_name="ga2web.nivadb.nivavcn.oraclevcn.com")
            con = cx_Oracle.connect(user='elata', password='elata', dsn=dsn_tns)
            cursor = con.cursor()
            gprint("Connesso al DB (ga2)")

            # Eseguire la prima query per ottenere CODMAT
            cursor.execute(queryMateriale, [codpref, nlotto, codice])
            result = cursor.fetchone()
            if result is not None:
                # Verificare se CODGUI non è nullo prima di accedere a result[1]
                if result[1] is not None:
                    # CODGUI non è nullo, eseguire queryTaglie
                    materiale = result[0]
                    cursor.execute(queryTaglie, {'nlotto': nlotto, 'codpref': codpref, 'materiale': materiale})
                else:
                    # CODGUI è nullo, eseguire queryPellami
                    materiale = result[0]
                    consumo_lotto = result[2]
                    cursor.execute(queryPellami, {'consumo_lotto': consumo_lotto, 'materiale': materiale})
            else:
                # Nessun risultato trovato per la prima query, restituisci un array vuoto
                return jsonify([]), 200

            rows = [dict(zip([column[0] for column in cursor.description], row)) for row in cursor.fetchall()]

            return jsonify(tuple(rows)), 200
        except Exception as e:
            rprint("Si è verificato un errore: " + str(e))
            traceback.print_exc()
            return jsonify({'Errore': str(e)}), 400
        finally:
            cursor.close()
            con.close()

@app.route('/api/get_info_ga2', methods=['POST'])
def get_info_ga2():
    gprint("Ricevuta richiesta get_info_ga2")
    salva_ip(request.remote_addr)
    data = request.get_json()  # Ottenere i dati inviati nella richiesta
    if 'codpref' not in data or 'nlotto' not in data:  # Verificare se sono presenti tutti i campi richiesti
        rprint("Richiesta malformata:\n" + str(data))
        return jsonify({'Errore': 'Campi mancanti'}), 400
    else:  # Parametri specificati correttamente
        codpref = int(data['codpref'])
        nlotto = int(data['nlotto'])
        queryInfo = """
            SELECT ocr.prefisso, (ocr.codart||'-' ||ocr.versione ) articolo
            FROM prod_lot_test   plt, PROD_LOT_RIG plr, ordcli_prefissi odf,
            ordcli_righe ocr, articoli a WHERE
            plt.prefisso = odf.prefisso
            and plt.prefisso = plr.prefisso
            and plt.nlotto = plr.nlotto
            AND plt.prefisso = odf.prefisso
            and plr.prefisso = ocr.prefisso
            and plr.nordcli = ocr.nordcli
            and plr.nrigocl = ocr.nrigocl
            and a.codart = ocr.codart
            AND odf.codpref = :codpref
            AND plt.nlotto = :nlotto
        """
        try:
            dsn_tns = cx_Oracle.makedsn("130.61.84.61", "1521", service_name="ga2web.nivadb.nivavcn.oraclevcn.com")
            con = cx_Oracle.connect(user='elata', password='elata', dsn=dsn_tns)
            cursor = con.cursor()
            gprint("Connesso al DB (ga2)")

            cursor.execute(queryInfo, [codpref, nlotto])
            result = cursor.fetchone()
            return jsonify(result), 200
        except Exception as e:
            rprint("Si è verificato un errore: " + str(e))
            traceback.print_exc()
            return jsonify({'Errore': str(e)}), 400
        finally:
            cursor.close()
            con.close()

@app.route('/api/get_tecnici_ga2', methods=['POST'])
def get_tecnici():
    gprint("Ricevuta richiesta get_tecnici")
    salva_ip(request.remote_addr)
    query = "SELECT * FROM RUBRICA_INTERVENTI"
    try:
        dsn_tns = cx_Oracle.makedsn("130.61.84.61", "1521", service_name="ga2web.nivadb.nivavcn.oraclevcn.com")
        con = cx_Oracle.connect(user='elata', password='elata', dsn=dsn_tns)
        gprint("Connesso al DB (ga2)")
        cursor = con.cursor()
        cursor.execute(query)
        rows = [dict(zip([column[0] for column in cursor.description], row)) for row in cursor.fetchall()]
        print("Risultato query: "+str(rows))
        return jsonify(tuple(rows)), 200
    except Exception as e:
        rprint("Si è verificato un errore: "+str(e))
        traceback.print_exc()
        return jsonify({'Errore': str(e),'success':False}), 400

@app.route('/api/sms', methods=['POST'])
def sms():

    def json_serial(obj): #JSON serializer for objects not serializable by default json code

        if isinstance(obj, datetime.datetime):
            serial = obj.isoformat()
            return serial

        raise TypeError ("Type not serializable")

    def login(username, password):
        """Authenticates the user given it's username and password. Returns a
        couple (user_key, session_key)
        """

        url = BASEURL+"login?username="+username+"&password="+password
        print("URL: "+url)
        r = requests.get(url)

        if r.status_code != 200:
            return None

        user_key, session_key = r.text.split(';')
        return user_key, session_key

    def sendSMS(auth, sendsms):
        headers = { 'user_key': auth[0],
                    'Session_key': auth[1],
                    'Content-type' : 'application/json' }

        r = requests.post(BASEURL+"sms",
                        headers=headers,
                        data=json.dumps(sendsms, default=json_serial))

        print(r)
        if r.status_code != 201:
            print("Error! http code: " + str(r.status_code) + ", body message: " + str(r.content))
            return None

        return json.loads(r.text)

    gprint("Ricevuta richiesta sms")
    salva_ip(request.remote_addr)
    data = request.get_json() # Ottenere i dati inviati nella richiesta
    if 'telefono' not in data or 'messaggio' not in data: # Verificare se sono presenti tutti i campi richiesti
        rprint("Richiesta malformata:\n"+str(data))
        return jsonify({'Errore': 'Campi mancanti'}), 400
    else: # Parametri specificati correttamente
        try:
            telefono = data['telefono']     # tecnico da chiamare
            messaggio = data['messaggio']   # messaggio da inviare
            if "+39" not in telefono:       # Controllo +39
                telefono = "+39"+telefono

            BASEURL = "https://app.esendex.it/API/v1.0/REST/"
            MESSAGE_HIGH_QUALITY = "N"
            MESSAGE_MEDIUM_QUALITY = "L"
            MESSAGE_LOW_QUALITY = "LL"
            user = "luca.valentino@niva.it"
            passw = "Casarano2023$"
            auth = login(user, passw)
            if not auth:
                rprint("Unable to login..")
                return jsonify({'Errore': str(e)}), 400
            else:
                sentSMS = sendSMS(auth,{
                    "message" : messaggio,
                    "message_type" : MESSAGE_HIGH_QUALITY,
                    "sender": "Elata",
                    "returnCredits" : False,
                    "recipient": [telefono,],
                })

                print(str(sentSMS))
                if sentSMS == None:
                    return jsonify({'success': False}), 400
                if sentSMS['result'] == "OK":
                    gprint("SMS sent!")
                    return jsonify({'success': True}), 200
        except Exception as e:
            rprint("Si è verificato un errore: "+str(e))
            traceback.print_exc()
            return jsonify({'Errore': str(e),'success':False}), 400

@app.route('/api/get_operatore_ga2', methods=['POST'])  # Ritorna l'operatore loggato col badge
def get_operatore_ga2():
    gprint("Ricevuta richiesta get_operatore_ga2")
    salva_ip(request.remote_addr)
    data = request.get_json() # Ottenere i dati inviati nella richiesta
    if 'codice_operatore' not in data: # Verificare se sono presenti tutti i campi richiesti
        rprint("Richiesta malformata:\n"+str(data))
        return jsonify({'Errore': 'Campi mancanti'}), 400
    else: # Parametri specificati correttamente
        try:
            codice_operatore = data['codice_operatore']
            query = "SELECT * FROM ANAGRAFICA WHERE CODICE_OPERATORE = :codice_operatore"
            dsn_tns = cx_Oracle.makedsn("130.61.84.61", "1521", service_name="ga2web.nivadb.nivavcn.oraclevcn.com")
            con = cx_Oracle.connect(user='elata', password='elata', dsn=dsn_tns)
            gprint("Connesso al DB (ga2)")
            cursor = con.cursor()
            cursor.execute(query, [codice_operatore])
            rows = [dict(zip([column[0] for column in cursor.description], row)) for row in cursor.fetchall()]
            print("Risultato query: "+str(rows))
            return jsonify(tuple(rows)), 200
        except Exception as e:
            rprint("Si è verificato un errore: "+str(e))
            traceback.print_exc()
            return jsonify({'Errore': str(e),'success': False}), 400

@app.route('/api/get_tecnico_ga2', methods=['POST'])    # Ritorna il tecnico
def get_tecnico_ga2():
    gprint("Ricevuta richiesta get_tecnico_ga2")
    salva_ip(request.remote_addr)
    data = request.get_json() # Ottenere i dati inviati nella richiesta
    if 'codice_tecnico' not in data: # Verificare se sono presenti tutti i campi richiesti
        rprint("Richiesta malformata:\n"+str(data))
        return jsonify({'Errore': 'Campi mancanti'}), 400
    else: # Parametri specificati correttamente
        try:
            codice_tecnico = data['codice_tecnico']
            query = "SELECT * FROM RUBRICA_INTERVENTI WHERE ID = :codice_tecnico"
            dsn_tns = cx_Oracle.makedsn("130.61.84.61", "1521", service_name="ga2web.nivadb.nivavcn.oraclevcn.com")
            con = cx_Oracle.connect(user='elata', password='elata', dsn=dsn_tns)
            gprint("Connesso al DB (ga2)")
            cursor = con.cursor()
            cursor.execute(query, [codice_tecnico])
            rows = [dict(zip([column[0] for column in cursor.description], row)) for row in cursor.fetchall()]
            print("Risultato query: "+str(rows))
            return jsonify(tuple(rows)), 200
        except Exception as e:
            rprint("Si è verificato un errore: "+str(e))
            traceback.print_exc()
            return jsonify({'Errore': str(e),'success': False}), 400

if __name__ == '__main__':
    gprint("Avvio server Flask")
    host='0.0.0.0'
    port=1404
    app.run(host,port)
