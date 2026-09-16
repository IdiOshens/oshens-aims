from django.urls import path
from . import views

app_name = 'registrations'

urlpatterns = [
    path('', views.registration_list, name='list'),
    path('attendance/', views.attendance_list, name='attendance'),
    path('join-meeting/<int:classroom_id>/', views.join_meeting, name='join_meeting'),
]
