{extends file="main.tpl"}

{block name=body}
<main>
	<div class="container d-flex align-items-center login-form">
		<form action="{url action="login"}"  method="post" class="w-100">
			<legend class="text-center mb-4">Logowanie</legend>
			<fieldset>
				<div class="mb-3">
					<label for="id_login" class="visually-hidden">Login: </label>
					<input id="id_login" type="text" name="login" value="" placeholder="login" class="form-control"/>
				</div>
				<div class="mb-3">
					<label for="id_pass" class="visually-hidden">Hasło: </label>
					<input id="id_pass" type="password" name="password" placeholder="hasło" class="form-control"/>
				</div>
			</fieldset>

			{if $msgs->isError()}
				{foreach $msgs->getErrors() as $msg}
					<div class="alert alert-danger mb-1">{$msg->text}</div>
				{/foreach}
			{/if}

			{if $msgs->isInfo()}
				{foreach $msgs->getInfos() as $msg}
					<div class="alert alert-info mb-1">{$msg->text}</div>
				{/foreach}
			{/if}

			<input type="submit" value="Zaloguj" class="btn btn-primary btn-lg w-100 mt-2"/>
		</form>	
	</div>	
</main>
{/block}