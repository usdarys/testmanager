{extends file="main.tpl"}
{block name=body}
<nav class="navbar bg-light border-bottom d-flex justify-content-between mb-3">
	<div>
		<a href="{url action="hello"}" class="btn btn-link">Hello</a>
	</div>
	<a href="{url action="logout"}" class="btn btn-link me-3">Wyloguj</a>
</nav>
<main>
    {block name=main}{/block}
</main>
{/block}