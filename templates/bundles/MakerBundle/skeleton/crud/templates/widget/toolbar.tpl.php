<div class="btn-group btn-group-sm mr-2" role="group" aria-label="Second group">
    <a  type="button"
        class="dropdown-toggle btn btn-outline-primary"
        data-toggle="dropdown"
        aria-haspopup="true"
        aria-expanded="false"
    ><i class="fa fa-cogs fa-sm"></i></a>
    <div class="dropdown-menu p-0">
        {% if <?= strtolower($entity_class_name) ?> is defined and <?= strtolower($entity_class_name) ?>.id > 0 %}

            <a
                class="dropdown-item py-2"
                href="{{url( '<?= strtolower($entity_class_name) ?>_editar')}}"
            ><i class="fa fa-plus-circle"></i> Agregar </a>

            <hr class="divider m-0">

        {% endif %}
    </div>
</div>

<div class="btn-group btn-group-sm mr-2" role="group" aria-label="Second group">
    <a
        href="https://bitbucket.org/bis86/arpe/wiki/<?= strtolower($entity_class_name) ?>"
        class="btn btn-outline-primary"
        target="_blank"
        title="Ayuda"
    ><i class="fa fa-question fa-lg"></i></a>
</div>