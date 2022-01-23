{extends file="main.tpl"}
{block name=body}
<nav class="navbar bg-light navbar-light navbar-expand-lg border-bottom mb-3">
	<div class="container-fluid">
		<a class="navbar-brand" href="#">Test Manager</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
	  	</button> 
	  	<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a href="{url action="hello"}" class="nav-link">Hello</a>
				</li>
				{if \core\RoleUtils::inRole("Admin")} 
					<li class="nav-item">
						<a class="nav-link" href="{url action="userList"}">Uzytkownicy</a>
					</li>
				{/if}
			</ul>
			<a href="{url action="logout"}" class="text-decoration-none">Wyloguj</a>
		</div>
	</div>
</nav>
<main>
    {block name=main}{/block}
</main>
{/block}


{* <nav class="navbar bg-light border-bottom d-flex justify-content-between mb-3">
	<div>
		<a href="{url action="hello"}" class="btn btn-link">Hello</a>
		{if \core\RoleUtils::inRole("Admin")} <a href="{url action="userList"}" class="btn btn-link">Uzytkownicy</a>{/if}
	</div>
	<a href="{url action="logout"}" class="btn btn-link me-3">Wyloguj</a>
</nav> *}