{% extends 'listar.html.twig' %}

{% block title %}Administrar <?= $entity_class_name ?>{% endblock %}

{% block addtoolbar %}
    {% include "<?= $route_path ?>/widget/toolbar.html.twig" %}
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
            class="sombrain p-0"
            data-url="{{ path(route ~ '_editar', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}"
            {# data-url="{{url( '<?= $route_name ?>_editar', {'slug': <?= $route_name ?>.slug})}}" #}
        >
            <td class="align-middle abrir p-0">
                <a
                    class="py-2"
                    href="{{ path( '<?= $route_name ?>_editar', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}"
                    {# href="{{url(  '<?= $route_name ?>_editar', {'slug': <?= $route_name ?>.slug})}}" #}
                ><i class="fa fa-pencil-alt"></i></a>
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