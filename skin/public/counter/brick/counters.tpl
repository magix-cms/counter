{counter_data}
{if isset($counters) && $counters != null}
    <section id="counters" class="clearfix">
        <div class="container">
            <div class="row">
                {foreach $counters as $counter}
                <div class="counter col" data-number="{$counter.number}">
                    <div class="number">0</div>
                    <div class="desc">
                        <p class="h4">{$counter.title}</p>
                        <p>{$counter.desc}</p>
                    </div>
                    {if $counter.url}<a href="{$counter.url}" title="{#read_more#} {$counter.title}" class="all-hover{if $counter.blank} targetblank{/if}"><span class="sr-only">{$counter.title}</span></a>{/if}
                </div>
                {/foreach}
            </div>
        </div>
    </section>
{/if}