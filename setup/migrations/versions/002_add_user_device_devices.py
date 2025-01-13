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
