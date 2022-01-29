{extends file="navbarView.tpl"}

{block name=main}
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
        <h4 class="border-bottom mb-3 mt-3">Przebiegi testów</h4>
        {include file="messages.tpl"}
        <ul class="nav mb-3 mt-3">
            {if \core\RoleUtils::inRoles(["Admin", "Test Leader"])} 
                <li class="nav-item">
                    <a href="{url action="testRunSave"}" class="btn btn-success">Dodaj</a>
                </li>
            {{/if}}
            <li>
                <form class="d-flex ms-3" action="{url action="testRunList"}" method="POST" >
                    <input class="form-control me-2" type="search" name="search" aria-label="Search" value="{$search}">
                    <button class="btn btn-outline-success btn-sm" type="submit">Szukaj</button>
                </form>
            </li>
            <li>
                <form class="d-flex ms-3" action="{url action="testRunList"}" method="POST" >
                <select class="form-select me-2" id="date_sorter" name="date_sorter">
                    <option value="desc" {if !$dateSorter || $dateSorter == "desc"}selected{/if}>od najnowszych</option>
                    <option value="asc" {if $dateSorter == "asc"}selected{/if}>od najstarszych</option>
                </select>
                <button class="btn btn-outline-success btn-sm" type="submit">Sortuj</button>
                </form>
            </li>
        </ul>
        <table class="table table-hover bg-light align-middle">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nazwa</th>
                <th scope="col">Data utworzenia</th>
                <th scope="col">Wykonane testy</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
        {foreach $testRunList as $testRun}
            <tr>
                <th scope="row">{$testRun["id"]}</th>
                <td><a href="{url action="testResultList" testRunId=$testRun["id"]}" class="text-decoration-none">{$testRun["name"]}</a></td>
                <td class="fw-normal text-muted">{$testRun["date_created"]}</td>
                <td class="fw-normal text-muted">{$testRun["tested"]} / {$testRun["all"]} ({round(($testRun["tested"]*100)/$testRun["all"], 2)}%)</td>
                <td class="d-flex justify-content-end">
                    <a href="{url action="testResultList" testRunId=$testRun["id"]}" class="text-decoration-none">></a>
                    {* <a href="{url action="testRunUpdate" id=$testRun["id"]}" class="btn btn-sm btn-outline-secondary me-2">Edytuj</a>
                    <a href="{url action="testRunDelete" id=$testRun["id"]}" class="btn btn-sm btn-outline-danger">Usuń</a> *}
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    </div>
{/block}