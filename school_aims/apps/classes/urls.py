from django.urls import path
from . import views

app_name = 'classes'

urlpatterns = [
    path('', views.class_list, name='list'),
    path('schedules/', views.schedule_list, name='schedules'),
]
