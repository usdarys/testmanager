{extends file="navbarView.tpl"}

{block name=main}
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
        <ul class="nav mb-3 mt-3">
            <li class="nav-item">
                <a href="{url action="testRunList"}" class="btn btn-sm btn-outline-success me-2">< Powrót</a>
            </li>       
        </ul>
        <h4 class="border-bottom mb-3 mt-3">{$testRun["name"]}</h4>
        <p class="p-3 border rounded bg-light">{$testRun["description"]}</p>
        {include file="messages.tpl"}
        <ul class="list-group list-group-flush mb-3 mt-3">
            <li class="list-group-item border-0 d-flex justify-content-center">Wykonane: {$testRunStats["tested"]} / {$testRunStats["all"]}&nbsp;<span class="text-muted"> ({100 - $testRunStats["untested_percent"]}%)</span></li>       
            <li class="list-group-item border-0 d-flex justify-content-center">Zaliczone: {$testRunStats["passed"]}&nbsp;<span class="text-muted"> ({$testRunStats["passed_percent"]}%)</span></li>
            <li class="list-group-item border-0 d-flex justify-content-center">Niezaliczone: {$testRunStats["failed"]}&nbsp;<span class="text-muted"> ({$testRunStats["failed_percent"]}%)</span></li>
        </ul>
        <div class="progress">
            <div class="progress-bar bg-success" role="progressbar" style="width: {$testRunStats["passed_percent"]}%"></div>
            <div class="progress-bar bg-danger" role="progressbar" style="width: {$testRunStats["failed_percent"]}%"></div>
            <div class="progress-bar bg-secondary" role="progressbar" style="width: {$testRunStats["untested_percent"]}%"></div>
        </div>


        <h6 class="mb-3 mt-5">Przypadki testowe:</h6>
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
        <table class="table table-hover bg-light align-middle">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nazwa</th>
                <th scope="col">Wykonawca</th>
                <th scope="col">Data aktualizacji</th>
                <th scope="col">Status</th>
                {* <th scope="col"></th> *}
            </tr>
        </thead>
        <tbody>
        {foreach $testResultList as $testResult}
            <tr>
                <th scope="row">{$testResult["id"]}</th>
                <td><a href="{url action="testResultUpdate" testRunId=$testRun["id"] testCaseId=$testResult["id"]}" class="text-decoration-none">{$testResult["name"]}</a></td>
                <td class="fw-normal text-muted">
                    {if isset($testResult["first_name"]) && isset($testResult["last_name"])}
                        {$testResult["first_name"]} {$testResult["last_name"]}
                    {else}
                        -
                    {/if}
                </td>
                <td class="fw-normal text-muted">{if isset($testResult["date_run"])}{$testResult["date_run"]}{else}-{/if}</td>
                <td>
                    {if $testResult["status"] == \app\types\TestResultStatusType::FAILED}
                        <span class="badge bg-danger">{$testResult["status"]}</span>
                    {elseif $testResult["status"] == \app\types\TestResultStatusType::PASSED}
                        <span class="badge bg-success">{$testResult["status"]}</span>
                    {else}
                        <span class="badge bg-secondary">{$testResult["status"]}</span>
                    {/if}
                </td>
                <td class="d-flex justify-content-end">
                    <a href="{url action="testResultUpdate" testRunId=$testRun["id"] testCaseId=$testResult["id"]}" class="text-decoration-none">></a>
                </td>
            </tr>
        {/foreach}
        </tbody>
    </table>
    </div>
{/block}