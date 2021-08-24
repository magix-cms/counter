{extends file="layout.tpl"}
{block name='head:title'}counter{/block}
{block name='body:id'}counter{/block}
{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="{#show_counter_list#}">Counter</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-xs-12 col-md-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header">
                    <h2 class="panel-heading h5">{if $edit}{#edit_counter#}{else}{#add_counter#}{/if}</h2>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    {include file="form/counter.tpl" controller="counter"}
                </div>
            </section>
        </div>
    {/if}
{/block}