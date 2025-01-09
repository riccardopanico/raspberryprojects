from alembic import op
import sqlalchemy as sa
from datetime import datetime
from app.models.user import User
from app.models.device import Device

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
            "username": "PiDevice1",
            "password_hash": "scrypt:32768:8:1$su2CPmxwlmis7UL3$88ba4f19a6368ba6934ed5d0ea9e92e275b23992f9ac0b6eb7b094e7667ef389563b4ee4386f5c569d1580ef4f5f4eecaf4a60ec348c6cc2fedd1b5686621a5c",
            "user_type": "device",
            "name": None,
            "last_name": None,
            "email": None,
            "created_at": created_at_value
        },
        {
            "id": 2,
            "badge": "0010452223",
            "username": "PiDevice2",
            "password_hash": "scrypt:32768:8:1$l6rlwqhhWBZi9BaY$cca8d1c83e8c0eba61ad2e4810798b26c12c196cb99c4298de9488b012d7f86c7a937f57ccc3c8cf81ade3f15d32685797a6dc35e5bc40580bf700adb5e57668",
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
            "id": 1,
            "device_id": 1,
            "user_id": 1,
            "mac_address": "00:1A:2B:3C:4D:5E",
            "ip_address": "192.168.0.97",
            "gateway": "192.168.0.1",
            "subnet_mask": "255.255.255.0",
            "dns_address": "8.8.8.8",
            "port_address": "8080",
            "username": "PiDevice1",
            "password": "PiDevice1",
            "created_at": created_at_value
        },
        {
            "id": 2,
            "device_id": 2,
            "user_id": 2,
            "mac_address": "00:1A:2B:3C:4D:5F",
            "ip_address": "192.168.0.93",
            "gateway": "192.168.0.1",
            "subnet_mask": "255.255.255.0",
            "dns_address": "8.8.8.8",
            "port_address": "8080",
            "username": "PiDevice2",
            "password": "PiDevice2",
            "created_at": created_at_value
        }
    ])
