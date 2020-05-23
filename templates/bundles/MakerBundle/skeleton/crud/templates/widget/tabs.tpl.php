<ul class="nav nav-tabs mb-3 border-bottom-warning">
    {% if proveedor is defined and proveedor.id > 0 %}
        <li class="nav-item">
            <a
                class="nav-link {% if app.request.get('_route') == '<?= strtolower($entity_class_name) ?>_editar' %}active{% endif %}"
                href="{{url('<?= strtolower($entity_class_name) ?>_editar',{'slug':<?= strtolower($entity_class_name) ?>.slug})}}"
            ><?= strtolower($entity_class_name) ?></a>
        </li>
    {% else %}
        <li class="nav-item">
            <a
                class="nav-link {% if app.request.get('_route') == '<?= strtolower($entity_class_name) ?>_editar' %}active{% endif %}"
                href="{{url('<?= strtolower($entity_class_name) ?>_editar')}}"
            ><?= strtolower($entity_class_name) ?></a>
        </li>
    {% endif %}
</ul>