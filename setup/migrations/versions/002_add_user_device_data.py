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
            "id": 6,
            "badge": "0010452223",
            "username": "PiDevice1",
            "password_hash": "scrypt:32768:8:1$pBsAj8fskHZBUtqP$e912d3b6a4361aa222f694384e20b6f3ec521f2c7ab6baff6bde3a0497779b056049c7b107b05ab3d5c2fe3a9ff1e3ba61dc750381c8a977145311553d4287f7",
            "user_type": "device",
            "name": None,
            "last_name": None,
            "email": None,
            "created_at": created_at_value
        }
    ])

    # Insert data into 'devices' table
    devices_table = Device.__table__
    op.bulk_insert(devices_table, [
        {
            "id": 5,
            "device_id": 1,
            "user_id": 6,
            "mac_address": "00:1A:2B:3C:4D:5E",
            "ip_address": "192.168.0.97",
            "gateway": "192.168.0.1",
            "subnet_mask": "255.255.255.0",
            "dns_address": "8.8.8.8",
            "port_address": "8080",
            "created_at": created_at_value
        }
    ])

    # Update 'device_id' in 'variables' table
    variables_table = Variables.__table__
    op.execute( variables_table.update().values(device_id=5) )
