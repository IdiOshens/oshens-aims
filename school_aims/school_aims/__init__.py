try:
    import pymysql
    pymysql.install_as_MySQLdb()
except ImportError:
    pass

# Python 3.14 compatibility patch for Django BaseContext.__copy__
try:
    import copy
    from django.template import context as django_context

    _orig_base_context_copy = django_context.BaseContext.__copy__
    def _patched_base_context_copy(self):
        cls = self.__class__
        duplicate = cls.__new__(cls)
        duplicate.__dict__.update(self.__dict__)
        duplicate.dicts = self.dicts[:]
        return duplicate

    django_context.BaseContext.__copy__ = _patched_base_context_copy
except Exception:
    pass

