{% extends 'form.html.twig' %}

{% block title %}Editar <?= $entity_class_name ?>{% endblock %}

{% block addtoolbar %}
    {% include "<?= $route_path ?>/widget/toolbar.html.twig" %}
{% endblock %}

{% block cont_form %}

    {% include "<?= $route_path ?>/widget/tabs.html.twig" %}

    <div class="row">
        <div class="col-12">
            <p class="lead m-0"><i class="fa fa-info-circle"></i> Ficha de <?= $entity_class_name ?></p>
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