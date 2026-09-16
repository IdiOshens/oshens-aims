from django.contrib import admin
from django.urls import path, include
from django.conf import settings
from django.conf.urls.static import static

urlpatterns = [
    path('admin/', admin.site.urls),
    path('accounts/', include('django.contrib.auth.urls')),
    path('accounts/', include('apps.users.urls')),
    path('dashboard/', include('apps.dashboard.urls')),
    path('subjects/', include('apps.subjects.urls')),
    path('classes/', include('apps.classes.urls')),
    path('classrooms/', include('apps.classrooms.urls')),
    path('registrations/', include('apps.registrations.urls')),
    path('notifications/', include('apps.notifications.urls')),
    path('', include('apps.dashboard.urls')),
]

if settings.DEBUG:
    from django.contrib.staticfiles.urls import staticfiles_urlpatterns
    urlpatterns += static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)
    urlpatterns += staticfiles_urlpatterns()
    import debug_toolbar
    urlpatterns += [path('__debug__/', include(debug_toolbar.urls))]

