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
    ])

    # Insert data into 'devices' table
    devices_table = Device.__table__
    op.bulk_insert(devices_table, [
    ])
