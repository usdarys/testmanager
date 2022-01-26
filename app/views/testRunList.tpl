{extends file="navbarView.tpl"}

{block name=main}
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
        <h4 class="border-bottom mb-3 mt-3">Przebiegi testów</h4>
        {include file="messages.tpl"}
        <ul class="nav mb-3 mt-3">
            <li class="nav-item">
                <a href="{url action="testRunSave"}" class="btn btn-success">Dodaj</a>
            </li>
            <li>
                <form class="d-flex ms-3" action="{url action="testRunList"}" method="POST" >
                    <input class="form-control me-2" type="search" name="search" aria-label="Search" value="{$search}">
                    <button class="btn btn-outline-success btn-sm" type="submit">Szukaj</button>
                </form>
            </li>
        </ul>
        <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nazwa</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        {foreach $testRunList as $testRun}
            <tr>
                <th scope="row">{$testRun["id"]}</th>
                <td>{$testRun["name"]}</td>
                <th></td>
                <td class="d-flex justify-content-end">
                    <a href="{url action="testResultList" testRunId=$testRun["id"]}" class="btn btn-sm btn-outline-secondary me-2">Otwórz ></a>
                    {* <a href="{url action="testRunUpdate" id=$testRun["id"]}" class="btn btn-sm btn-outline-secondary me-2">Edytuj</a>
                    <a href="{url action="testRunDelete" id=$testRun["id"]}" class="btn btn-sm btn-outline-danger">Usuń</a> *}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    </div>
{/block}