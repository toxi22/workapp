<ul class="nav nav-list mainMenu">
<li class="nav-header">Find:<li>

{% if registry.args.0 == "objects" %}<li class="active">{% else %}<li>
{% endif %}
    <a href="{{ registry.uri }}find/objects/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/books-stack.png" alt="" border="0" />
    Objects [{{ num.obj }}]</a>
</li>

{% if registry.args.0 == "tasks" %}<li class="active">{% else %}<li>
{% endif %}
    <a href="{{ registry.uri }}find/tasks/"><img style="vertical-align: middle" src="{{ registry.uri }}img/left/task.png" alt="" border="0" />
    Tasks [{{ num.tasks }}]</a>
</li>

{% if registry.args.0 == "adv" %}<li class="active">{% else %}<li>
{% endif %}
    <a href="{{ registry.uri }}find/adv/"><img style="vertical-align: middle" src="{{ registry.uri }}img/information-button.png" alt="" border="0" />
    Info [{{ num.advs }}]</a>
</li>

</ul>