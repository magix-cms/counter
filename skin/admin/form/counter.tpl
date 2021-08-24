<div class="row">
    <form id="edit_counter" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;tabs=counter&amp;action={if !$edit}add{else}edit{/if}" method="post" class="validate_form{if !$edit} add_form collapse in{else} edit_form{/if} col-ph-12 col-sm-8 col-md-6">
        <div class="row">
            <div class="form-group col-ph-12 col-sm-6 col-md-4">
                <label for="number_counter">{#number_counter#} :</label>
                <input type="number" min="0" step="1" class="form-control" id="number_counter" name="counter[number_counter]" value="{if isset($counter)}{$counter.number_counter}{else}0{/if}" />
            </div>
        </div>
        {include file="language/brick/dropdown-lang.tpl"}
        <div class="row">
            <div class="col-ph-12">
                <div class="tab-content">
                    {foreach $langs as $id => $iso}
                        <div role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                            <fieldset>
                                <legend>{#content_counter#}</legend>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                                        <div class="form-group">
                                            <label for="title_counter_{$id}">{#title_counter#} :</label>
                                            <input type="text" class="form-control" id="title_slide_{$id}" name="counter[content][{$id}][title_counter]" value="{$counter.content[{$id}].title_counter}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="desc_counter_{$id}">{#content_counter#} :</label>
                                            <textarea class="form-control" id="desc_counter_{$id}" name="counter[content][{$id}][desc_counter]" cols="65" rows="3">{$counter.content[{$id}].desc_counter}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="url_counter">{#url_counter#}</label>
                                            <input id="url_counter" class="form-control" type="text" size="150" name="counter[content][{$id}][url_counter]" value="{$counter.content[{$id}].url_counter}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="blank_counter_{$id}">{#blank_counter#}</label>
                                            <div class="switch">
                                                <input type="checkbox" id="blank_counter_{$id}" name="counter[content][{$id}][blank_counter]" class="switch-native-control"{if $counter.content[{$id}].blank_counter} checked{/if} />
                                                <div class="switch-bg">
                                                    <div class="switch-knob"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <fieldset>
            <legend>{#save#|ucfirst}</legend>
            {if $edit}<input type="hidden" name="counter[id_counter]" value="{$counter.id_counter}"/>{/if}
            <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </fieldset>
    </form>
</div>