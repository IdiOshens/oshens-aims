from django.shortcuts import render, get_object_or_404
from django.contrib.auth.decorators import login_required
from .models import SchoolClass, ClassSchedule


@login_required
def class_list(request):
    classes = SchoolClass.objects.select_related('subject', 'teacher').all()
    return render(request, 'classes/class_list.html', {'classes': classes})


@login_required
def schedule_list(request):
    schedules = ClassSchedule.objects.select_related('school_class', 'classroom', 'school_class__teacher').all()
    return render(request, 'classes/schedule_list.html', {'schedules': schedules})
