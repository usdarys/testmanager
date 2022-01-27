{extends file="navbarView.tpl"}

{block name=main}
	<div class="container d-flex justify-content-center credit-calc-form flex-column">
        <ul class="nav mb-3 mt-3">
            <li class="nav-item">
                <a href="{url action="testResultList" testRunId="{$form->testRunId}"}" class="btn btn-sm btn-outline-success me-2">< Powrót</a>
            </li>       
        </ul>
        <h4 class="border-bottom mb-3 mt-3">{$form->testCaseName}</h4>
        <form action="{url action="testResultSave"}"  method="post" class="mb-3">
            <input type="hidden" name="test_run_id" value="{$form->testRunId}">
            <input type="hidden" name="test_case_id" value="{$form->testCaseId}">
            <div class="mb-3">
                <label for="test_case_preconditions_id" class="form-label">Warunki wstepne</label>
                <textarea class="form-control" id="test_case_preconditions_id" rows="3" name="test_case_preconditions" readonly>{$form->testCasePreconditions}</textarea>
            </div>
            <div class="mb-3">
                <label for="test_case_steps_id" class="form-label">Kroki</label>
                <textarea class="form-control" id="test_case_steps_id" rows="6" name="test_case_steps" readonly>{$form->testCaseSteps}</textarea>
            </div>
            <div class="mb-3">
                <label for="test_case_expected_result_id" class="form-label">Oczekiwany rezultat</label>
                <textarea class="form-control" id="test_case_expected_result_id" rows="3" name="test_case_expected_result" readonly>{$form->testCaseExpectedResult}</textarea>
            </div>
            {* <select class="form-select">
                <option selected>Open this select menu</option>
                <option value="1">One</option>
                <option value="2">Two</option>
                <option value="3">Three</option>
            </select> *}
            <div>Status: {$form->status}</div>
            <div class="mb-3">
                <label for="comment_id" class="form-label">Komentarz</label>
                <textarea class="form-control" id="comment_id" rows="3" name="comment">{$form->comment}</textarea>
            </div>
            <input type="submit" value="Zapisz wynik" class="btn btn-success">
        </form>
        {include file="messages.tpl"}
    </div>
{/block}