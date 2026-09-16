from django.shortcuts import get_object_or_404, redirect
from django.http import JsonResponse
from django.contrib.auth.decorators import login_required
from .models import Notification


@login_required
def mark_as_read(request, notification_id):
    notification = get_object_or_404(Notification, id=notification_id, user=request.user)
    notification.is_read = True
    notification.save()
    
    if request.headers.get('x-requested-with') == 'XMLHttpRequest' or request.GET.get('ajax'):
        return JsonResponse({'success': True, 'message': 'Notification marked as read.'})
    return redirect('dashboard:home')


@login_required
def mark_all_read(request):
    Notification.objects.filter(user=request.user, is_read=False).update(is_read=True)
    if request.headers.get('x-requested-with') == 'XMLHttpRequest' or request.GET.get('ajax'):
        return JsonResponse({'success': True, 'message': 'All notifications marked as read.'})
    return redirect('dashboard:home')
