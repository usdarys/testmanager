{extends file="navbarView.tpl"}

{block name=main}
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
        <h4 class="border-bottom mb-3 mt-3">{if empty($form->id)}Nowy przebieg testów{else}Edycja przebiegu testów{/if}</h4>
        <form action="{url action="testRunSave"}"  method="post" class="mb-3">
            <input type="hidden" name="id" value="{$form->id}">
            <div class="mb-3">
                <label for="name_id" class="form-label">Nazwa</label>
                <input type="text" class="form-control" id="name_id" name="name" value="{$form->name}">
            </div>
            <div class="mb-3">
                <label for="description_id" class="form-label">Opis</label>
                <textarea class="form-control" id="description_id" rows="3" name="description">{$form->description}</textarea>
            </div>
            <input type="submit" value="{if empty($form->id)}Dodaj{else}Zapisz{/if} przebieg testów" class="btn btn-success">
            <a href="{url action="testRunList"}" class="btn btn-outline-danger">Anuluj</a>
        </form>
        {include file="messages.tpl"}
    </div>
{/block}