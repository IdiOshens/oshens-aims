from django.shortcuts import render, get_object_or_404, redirect
from django.contrib.auth.decorators import login_required
from django.contrib import messages
from .models import Subject


@login_required
def subject_list(request):
    subjects = Subject.objects.select_related('head_teacher').all()
    return render(request, 'subjects/subject_list.html', {'subjects': subjects})
