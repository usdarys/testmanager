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
                <h6>Warunki wstepne</h6>
                <p style="white-space: pre-line;" class="p-3 border rounded bg-light">{if !empty(htmlspecialchars_decode($form->testCasePreconditions))}{$form->testCasePreconditions}{else}-{/if}</p>
            </div>
            <div class="mb-3">
                <h6>Kroki</h6>
                <p style="white-space: pre-line;" class="p-3 border rounded bg-light">{htmlspecialchars_decode($form->testCaseSteps)}</p>
            </div>
            <div class="mb-3">
                <h6>Oczekiwany rezultat</h6>
                <p style="white-space: pre-line;" class="p-3 border rounded bg-light">{htmlspecialchars_decode($form->testCaseExpectedResult)}</p>
            </div>
            <div class="mb-3 w-25">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    {foreach $statusList as $s}
                        <option value={$s} {if $s == $form->status}selected{/if}>{$s}</option>
                    {/foreach}
                </select>
            </div>
            <div class="mb-3">
                <label for="comment_id" class="form-label">Komentarz</label>
                <textarea class="form-control" id="comment_id" rows="3" name="comment">{htmlspecialchars_decode($form->comment)}</textarea>
            </div>
            <input type="submit" value="Zapisz wynik" class="btn btn-success">
        </form>
        {include file="messages.tpl"}
    </div>
{/block}