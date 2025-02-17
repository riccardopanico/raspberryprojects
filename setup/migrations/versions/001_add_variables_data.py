from alembic import op
import sqlalchemy as sa
from app.models.variables import Variables
from datetime import datetime

# Revision identifiers, used by Alembic.
revision = None
down_revision = None
branch_labels = None
depends_on = None

def upgrade():
    variables_table = Variables.__table__
    created_at_value = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    op.bulk_insert(variables_table, [
        {"variable_code": "commessa", "variable_name": "Commessa", "string_value": '123', "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "interconnection_id", "variable_name": "ID Macchina", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "user_id", "variable_name": "ID Utente", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "articolo", "variable_name": "Articolo", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "data_cambio_olio", "variable_name": "Data Cambio Olio", "string_value": "07/11/2024 16:28:37", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "data_cambio_spola", "variable_name": "Data Cambio Spola", "string_value": "12/11/2024 16:24:36", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "distanza_reset_olio", "variable_name": "Distanza Reset Olio", "string_value": None, "numeric_value": 100, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "dns_nameservers", "variable_name": "DNS Name Servers", "string_value": "8.8.8.8", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "fattore_taratura", "variable_name": "Fattore Taratura", "string_value": None, "numeric_value": 78, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "gateway", "variable_name": "Gateway", "string_value": "192.168.10.253", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "badge", "variable_name": "Badge", "string_value": "0010452223", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "ip_local_server", "variable_name": "IP Server Locale", "string_value": "192.168.0.245", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "ip_macchina", "variable_name": "IP Macchina", "string_value": "192.168.10.201", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "last_barcode", "variable_name": "Ultimo Barcode Scansionato", "string_value": "123", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "manuale_uso", "variable_name": "Manuale d'uso", "string_value": "manuale_uso.pdf", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "network_interface", "variable_name": "Interfaccia di Rete", "string_value": "wlan0", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "network_name", "variable_name": "Nome Rete", "string_value": "elata", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "network_password", "variable_name": "Password Rete", "string_value": "Elata1923", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "parametro_olio", "variable_name": "Parametro Olio (ore)", "string_value": None, "numeric_value": 2, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "parametro_olio_attivo", "variable_name": "Parametro Olio Attivo", "string_value": None, "numeric_value": None, "boolean_value": 0, "device_id": None, "created_at": created_at_value},
        {"variable_code": "parametro_spola", "variable_name": "Parametro Spola (metri)", "string_value": None, "numeric_value": 100, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "parametro_spola_attivo", "variable_name": "Parametro Spola Attivo", "string_value": None, "numeric_value": None, "boolean_value": 0, "device_id": None, "created_at": created_at_value},
        {"variable_code": "porta_local_server", "variable_name": "Porta Server Locale", "string_value": None, "numeric_value": 5000, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "richiesta_filato", "variable_name": "Richiesta Filato", "string_value": None, "numeric_value": None, "boolean_value": 0, "device_id": None, "created_at": created_at_value},
        {"variable_code": "richiesta_intervento", "variable_name": "Richiesta Intervento", "string_value": None, "numeric_value": None, "boolean_value": 0, "device_id": None, "created_at": created_at_value},
        {"variable_code": "subnet", "variable_name": "Subnet", "string_value": "255.255.255.0", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "websocket_host", "variable_name": "WebSocket Host", "string_value": "raspberryprojects.local", "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "websocket_port", "variable_name": "WebSocket Port", "string_value": None, "numeric_value": 8765, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "encoder_consumo", "variable_name": "Consumo Encoder", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "encoder_operativita", "variable_name": "Operatività Encoder", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "encoder_impulsi", "variable_name": "Impulsi Encoder", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "lotto", "variable_name": "Lotto", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "prefisso", "variable_name": "Prefisso", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "PE1situazione", "variable_name": "Pelle 1", "string_value": '[]', "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "PE2situazione", "variable_name": "Pelle 2", "string_value": '[]', "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "PE3situazione", "variable_name": "Pelle 3", "string_value": '[]', "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "SUsituazione", "variable_name": "Suole Situazione/Ordinato", "string_value": '[]', "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "T1codlavor", "variable_name": "Codice Lavorante", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "T1data_lavor", "variable_name": "Data Lavorazione", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "TCcodlavor", "variable_name": "Codice Lavorante", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "TCdata_lavor", "variable_name": "Data Lavorazione", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "note_lavorazione", "variable_name": "Note Lavorazione", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "api_base_url", "variable_name": "API Base URL", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "id_azienda", "variable_name": "ID Azienda", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
        {"variable_code": "api_key", "variable_name": "API Key", "string_value": None, "numeric_value": None, "boolean_value": None, "device_id": None, "created_at": created_at_value},
    ]
)
