from django.shortcuts import render, redirect
from django.contrib.auth.decorators import login_required, user_passes_test
from django.contrib import messages
from django.contrib.auth import login
from .forms import UserRegistrationForm

@login_required
def profile(request):
    user = request.user
    role_object = user.get_role_object()
    return render(request, 'users/profile.html', {'user': user, 'role_object': role_object})

@login_required
def update_profile(request):
    if request.method == 'POST':
        user = request.user
        user.first_name = request.POST.get('first_name', '')
        user.last_name = request.POST.get('last_name', '')
        user.phone = request.POST.get('phone', '')
        user.save()
        messages.success(request, 'Profile updated successfully')
        return redirect('users:profile')
    return render(request, 'users/profile_edit.html', {'user': request.user})

@user_passes_test(lambda u: u.is_superuser or getattr(u, 'role', '') == 'admin')
def register(request):
    if request.method == 'POST':
        form = UserRegistrationForm(request.POST)
        if form.is_valid():
            user = form.save()
            messages.success(request, f'User {user.email} created successfully')
            return redirect('admin:users_user_changelist')
    else:
        form = UserRegistrationForm()
    return render(request, 'users/register.html', {'form': form})
