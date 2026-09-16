from django.shortcuts import render, get_object_or_404
from django.contrib.auth.decorators import login_required
from .models import Classroom


@login_required
def classroom_list(request):
    classrooms = Classroom.objects.all()
    return render(request, 'classrooms/classroom_list.html', {'classrooms': classrooms})
