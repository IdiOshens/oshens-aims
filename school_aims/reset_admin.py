import os
import django

os.environ.setdefault('DJANGO_SETTINGS_MODULE', 'school_aims.settings')
django.setup()

from apps.users.models import User

# Reset or create admin
email = 'admin@school.com'
password = 'admin123'

try:
    user = User.objects.get(email=email)
    user.set_password(password)
    user.is_staff = True
    user.is_superuser = True
    user.role = 'admin'
    user.save()
    print(f'[OK] Admin password reset for {email}')
except User.DoesNotExist:
    user = User.objects.create_superuser(
        email=email,
        password=password,
        first_name='System',
        last_name='Admin'
    )
    print(f'[OK] Admin created: {email}')

print(f'  Email: {email}')
print(f'  Password: {password}')
