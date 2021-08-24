{extends file="layout.tpl"}
{block name='head:title'}counter{/block}
{block name='body:id'}counter{/block}
{block name="styleSheet" append}
    {include file="css.tpl"}
{/block}
{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="pull-right">
            <p class="text-right">
                <a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_counter_btn#}" class="btn btn-link">
                    <span class="fa fa-plus"></span> {#add_counter_btn#|ucfirst}
                </a>
            </p>
        </div>
    {/if}
    <h1 class="h2">Counter</h1>
{/block}
{block name="article:content"}
    {if {employee_access type="view" class_name=$cClass} eq 1}
    <div class="panels row">
        <section class="panel col-ph-12">
            {if $debug}
                {$debug}
            {/if}
            <header class="panel-header">
                <h2 class="panel-heading h5">{#root_counter#}</h2>
            </header>
            <div class="panel-body panel-body-form">
                <div class="mc-message-container clearfix">
                    <div class="mc-message"></div>
                </div>
                {include file="section/form/table-form-3.tpl" idcolumn='id_counter' data=$counters activation=false sortable=true controller="counter" change_offset=false}
            </div>
        </section>
    </div>
    {include file="modal/delete.tpl" data_type='address' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_counter_message#}}
    {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}

{block name="foot" append}
    {capture name="scriptForm"}{strip}
        /{baseadmin}/min/?f=libjs/vendor/jquery-ui-1.12.min.js,
        {baseadmin}/template/js/table-form.min.js
    {/strip}{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        $(function(){
            var controller = "{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}";
            var offset = "{if isset($offset)}{$offset}{else}null{/if}";
            if (typeof tableForm == "undefined")
            {
                console.log("tableForm is not defined");
            }else{
                tableForm.run(controller,offset);
            }
        });
    </script>
{/block}