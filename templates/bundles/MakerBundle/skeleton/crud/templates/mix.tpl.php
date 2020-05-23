{% extends 'mix.html.twig' %}

{% block title %}
    {% if contactoActual is defined and contactoActual.id > 0  %}
        Editar <?= $entity_class_name ?>
    {% else %}
        Crear <?= $entity_class_name ?>
    {% endif %}
{% endblock %}

{% block addtoolbar %}
    {# {% include "<?= $route_path ?>/widget/toolbar.html.twig" %} #}
{% endblock %}

{% block cont_form %}

    {# {% include "<?= $route_path ?>/widget/tabs.html.twig" %} #}

    <div class="row">
        <div class="col-12">
            {% if contactoActual is defined and contactoActual.id > 0  %}
                <p class="lead m-0 p-2 bg-warning"><i class="fa fa-edit"></i> Editar <?= $entity_class_name ?></p>
            {% else %}
                <p class="lead m-0 p-2"><i class="fa fa-info-circle"></i> Nuevo <?= $entity_class_name ?></p>
            {% endif %}
            <hr class="divider">
        </div>
    </div>

    <?php foreach ($entity_fields as $field): ?>
        <?php if (
                $field['fieldName'] != 'id'
            and  $field['fieldName'] != 'slug'
            and  $field['fieldName'] != 'updatedAt'
            and  $field['fieldName'] != 'createdAt' ):
        ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group form-group-lg">
                <label class="small m-0"><?= $field['fieldName'] ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-bold bg-white">
                            <i class="fa fa-portrait"></i>
                        </span>
                    </div>
                    {{ form_widget(form.<?= $field['fieldName'] ?>) }}
                </div>
                <div class="form_errors small">{{ form_errors(form.<?= $field['fieldName'] ?>) }}</div>
            </div>
        </div>
    </div>

    <?php endif; ?>
    <?php endforeach; ?>

{% endblock %}

{% block thead %}
    <tr  class="p-0">

        <th class="text-left text-muted p-0" colspan="2"></th>
    <?php foreach ($entity_fields as $field): ?>
        <?php if(
                 $field['fieldName'] != 'id'
            and  $field['fieldName'] != 'slug'
            and  $field['fieldName'] != 'updatedAt'
            and  $field['fieldName'] != 'createdAt'
        ): ?>
        <th class="text-left text-muted p-0">
            <p class="m-0 small"><?= strtoupper($field['fieldName']) ?></p>
        </th>
        <?php endif; ?>
    <?php endforeach; ?>

    </tr>
{% endblock %}

{% block tbody  %}
    {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
        <tr
            class="sombrain p-0 text-truncate"
            class="sombrain p-0 text-truncate {% if <?= $entity_twig_var_singular ?>Actual is defined and <?= $entity_twig_var_singular ?>.id == <?= $entity_twig_var_singular ?>Actual.id %}bg-warning{% endif %}"
            data-url="{{ path(route ~ '_editar', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}"
            {# data-url="{{url( '<?= $route_name ?>_editar', {'slug': <?= $route_name ?>.slug})}}" #}
        >
            <td class="align-middle abrir p-0">

            </td>

            <td class="text-left abrir p-1">
                <p class="m-0 badge badge-primary small rounded-sm"><small>ID-{{"%05d"|format(row.id)}}</small></p>
            </td>

            <?php foreach ($entity_fields as $field): ?>
                <?php if(
                        $field['fieldName'] != 'id'
                    and  $field['fieldName'] != 'slug'
                    and  $field['fieldName'] != 'updatedAt'
                    and  $field['fieldName'] != 'createdAt'
                ): ?>

                    <td class="text-left abrir p-1">
                        <p class="m-0">{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</p>
                    </td>
                <?php endif; ?>
            <?php endforeach; ?>

        </tr>
    {% endfor %}
{% endblock %}