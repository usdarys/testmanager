{if $msgs->isMessage()}
    {foreach $msgs->getMessages() as $msg}
        <div class="alert alert-{if $msg->isError()}danger{/if}{if $msg->isWarning()}warning{/if}{if $msg->isInfo()}info{/if} mb-1">{$msg->text}</div>
    {/foreach}
{/if}