{extends file="navbarView.tpl"}

{block name=main}
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
        <ul class="nav mb-3 mt-3">
            <li class="nav-item">
                <a href="{url action="testRunList"}" class="btn btn-sm btn-outline-success me-2">< Powrót</a>
            </li>       
        </ul>
        <h4 class="border-bottom mb-3 mt-3">{$testRun["name"]}</h4>
        <p>{$testRun["description"]}</p>
        <h6 class="mb-3 mt-3">Przypadki testowe ({$testRunStats["tested"]}/{$testRunStats["all"]}):</h6>
        {include file="messages.tpl"}
        {* <ul class="nav mb-3 mt-3">
            <li class="nav-item">
                <a href="{url action="testResultSave"}" class="btn btn-success">Dodaj</a>
            </li>
            <li>
                <form class="d-flex ms-3" action="{url action="testResultList"}" method="POST" >
                    <input class="form-control me-2" type="search" name="search" aria-label="Search" value="{}">
                    <button class="btn btn-outline-success btn-sm" type="submit">Szukaj</button>
                </form>
            </li>
        </ul> *}
        <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nazwa</th>
                <th scope="col">Wykonawca</th>
                <th scope="col">Data wykonania</th>
                <th scope="col">Status</th>
                {* <th scope="col"></th> *}
            </tr>
        </thead>
        <tbody>
        {foreach $testResultList as $testResult}
            <tr>
                <th scope="row">{$testResult["id"]}</th>
                <td>{$testResult["name"]}</td>
                <td>
                    {if isset($testResult["first_name"]) && isset($testResult["last_name"])}
                        {$testResult["first_name"]} {$testResult["last_name"]}
                    {else}
                        -
                    {/if}
                </td>
                <td>{if isset($testResult["date_run"])}{$testResult["date_run"]}{else}-{/if}</td>
                <td>
                    {if $testResult["status"] == \app\types\TestResultStatusType::FAILED}
                        <span class="badge bg-danger">{$testResult["status"]}</span>
                    {elseif $testResult["status"] == \app\types\TestResultStatusType::PASSED}
                        <span class="badge bg-success">{$testResult["status"]}</span>
                    {else}
                        <span class="badge bg-secondary">{$testResult["status"]}</span>
                    {/if}
                </td>
                {* <td class="d-flex justify-content-end">
                    <a href="{url action="testResultUpdate" id=$testResult["id"]}" class="btn btn-sm btn-outline-secondary me-2">Edytuj</a>
                    <a href="{url action="testResultDelete" id=$testResult["id"]}" class="btn btn-sm btn-outline-danger">Usuń</a>
                </td>*}
            </tr>
        {/foreach}
        </tbody>
    </table>
    </div>
{/block}