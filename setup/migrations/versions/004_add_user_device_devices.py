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
            "badge": "0010452223",
            "username": "fake_username",
            "password_hash": "fake_password_hash",
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
            "user_id": None,
            "mac_address": "00:1A:2B:3C:4D:5E",
            "ip_address": "192.168.0.93",
            "gateway": None,
            "subnet_mask": None,
            "dns_address": None,
            "port_address": None,
            "username": None,
            "password": None,
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
