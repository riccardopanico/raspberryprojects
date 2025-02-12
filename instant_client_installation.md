# Installazione di Oracle Instant Client su Ubuntu 24.04

## 1️⃣ Scaricare i pacchetti Oracle Instant Client
Scaricare i pacchetti da:
🔗 [Oracle Instant Client](https://www.oracle.com/database/technologies/instant-client/linux-x86-64-downloads.html)

Scaricare almeno:
- **Basic Package** (Obbligatorio)
- **SQL*Plus Package** (Opzionale, se vuoi usare SQL*Plus)
- **SDK Package** (Opzionale, se serve per sviluppo)

## 2️⃣ Estrarre i pacchetti
```bash
sudo mkdir -p /opt/oracle
cd /opt/oracle
sudo unzip ~/Downloads/instantclient*.zip
```

La directory risultante sarà simile a:
```bash
/opt/oracle/instantclient_19_26
```

## 3️⃣ Creare i link simbolici
```bash
cd /opt/oracle/instantclient_19_26
sudo ln -s libclntsh.so.19.1 libclntsh.so
sudo ln -s libocci.so.19.1 libocci.so
```

## 4️⃣ Installare le dipendenze richieste
```bash
sudo apt update
sudo apt install libaio1 libaio-dev -y
```

## 5️⃣ Aggiungere Oracle Instant Client alla variabile `LD_LIBRARY_PATH`
### Per un singolo utente:
```bash
echo 'export LD_LIBRARY_PATH=/opt/oracle/instantclient_19_26:$LD_LIBRARY_PATH' >> ~/.bashrc
source ~/.bashrc
```
### Per tutti gli utenti:
```bash
echo 'LD_LIBRARY_PATH=/opt/oracle/instantclient_19_26:$LD_LIBRARY_PATH' | sudo tee -a /etc/environment
source /etc/environment
```
### Alternativa con `ldconfig`:
```bash
echo "/opt/oracle/instantclient_19_26" | sudo tee /etc/ld.so.conf.d/oracle-instantclient.conf
sudo ldconfig
```

## 6️⃣ Verificare l'installazione
Controllare se le librerie sono correttamente riconosciute:
```bash
ldd /opt/oracle/instantclient_19_26/libclntsh.so
```
Deve mostrare percorsi validi, senza errori `not found`.

Verificare che SQL*Plus funzioni:
```bash
/opt/oracle/instantclient_19_26/sqlplus -v
```

## 7️⃣ (Opzionale) Installare il driver Python `cx_Oracle`
```bash
pip install cx_Oracle
```
Verificare:
```python
import cx_Oracle
print(cx_Oracle.clientversion())
```

## ✅ Ora Oracle Instant Client è installato correttamente su Ubuntu 24.04!

