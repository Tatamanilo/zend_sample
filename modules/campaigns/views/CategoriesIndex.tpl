<script type="text/javascript" src="{$jsPath}jsTree3/jstree.js"></script>
<link rel="stylesheet" href="{$jsPath}jsTree3/themes/default/style.min.css" type="text/css">
{load_javascript src="view/campaigns.categories.view.js"}
{load_javascript src="view/campaigns.categories.add.js"}
{load_javascript src="view/campaigns.categories.edit.js"}

<script type="text/javascript">
    {literal}
    $(document).ready(function(){
        categoriesViewStartup();
    });
    {/literal}
</script>

<div class="row">
    <div class="col-lg-12 text-right">
        <input class="btn btn-primary " type="button" value="{$messages.addCategory}" onclick="return categoriesAddShow();">
    </div>
    <div class="clearfix"></div>
</div>

<div id="categoriesContent">

</div>

<div id="dialogEdit" title="{$messages.editCategory}"></div>
<div id="dialogAdd" title="{$messages.addCategory}"></div>

