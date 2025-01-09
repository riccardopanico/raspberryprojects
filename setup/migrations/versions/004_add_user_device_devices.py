from alembic import op
import sqlalchemy as sa
from datetime import datetime
from app.models.user import User
from app.models.device import Device
from app.models.variables import Variables

# Revision identifiers, used by Alembic.
revision = None
down_revision = None
branch_labels = None
depends_on = None

def upgrade():
    # Insert data into 'users' table
    users_table = User.__table__
    created_at_value = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    op.bulk_insert(users_table, [
        {
            "id": 1,
            "badge": None,
            "username": "LocalDevice",
            "password_hash": "scrypt:32768:8:1$localdevice$fakepasswordhash",  # Placeholder
            "user_type": "local_device",
            "name": None,
            "last_name": None,
            "email": None,
            "created_at": created_at_value
        },
        {
            "id": 2,
            "badge": None,
            "username": "DataCenter",
            "password_hash": "scrypt:32768:8:1$yWOOm5wNPVgnyhRj$0867fe64c2733eb00f5394117307ba8953d2e9e621da2ba79d839f435d84348512f02532c57ec9b6a44ddb84f529d8ab62053d3a4852fac4a69b75037115a64b",
            "user_type": "datacenter",
            "name": None,
            "last_name": None,
            "email": None,
            "created_at": created_at_value
        },
        {
            "id": 3,
            "badge": "0010452223",
            "username": "HumanUser1",
            "password_hash": "scrypt:32768:8:1$zR6Lhg1wDPl5nCyE$324f4a5cbcd8de44c524a547223db1295e5d31472f28b3733826d2b48c96b8c17825135833e373874aad3eb0c88e91d4b2844b01df12c6ae4bb3b700aad14462",
            "user_type": "human",
            "name": "John",
            "last_name": "Doe",
            "email": "john.doe@example.com",
            "created_at": created_at_value
        }
    ])

    # Insert data into 'devices' table
    devices_table = Device.__table__
    op.bulk_insert(devices_table, [
        {
            "id": 1,
            "device_id": None,
            "user_id": 1,  # Associato all'utente fittizio local_device
            "mac_address": "00:1A:2B:3C:4D:5F",
            "ip_address": "192.168.0.100",  # Indirizzo IP del dispositivo locale
            "gateway": "192.168.0.1",
            "subnet_mask": "255.255.255.0",
            "dns_address": "8.8.8.8",
            "port_address": "8081",
            "username": None,  # Nessuna credenziale necessaria
            "password": None,  # Nessuna credenziale necessaria
            "created_at": created_at_value
        },
        {
            "id": 2,
            "device_id": None,
            "user_id": 2,  # Associato al datacenter
            "mac_address": "00:1A:2B:3C:4D:5E",
            "ip_address": "192.168.0.245",
            "gateway": "192.168.0.1",
            "subnet_mask": "255.255.255.0",
            "dns_address": "8.8.8.8",
            "port_address": "8080",
            "username": "DataCenter",
            "password": "DataCenter",
            "created_at": created_at_value
        }
    ])

    # Update 'device_id' in 'variables' table
    variables_table = Variables.__table__
    op.execute(
        variables_table.update()
        .where(variables_table.c.device_id == None)  # Aggiorna solo le variabili senza device associato
        .values(device_id=1)  # Associa il dispositivo locale
    )
