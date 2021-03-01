import { Component, OnDestroy, OnInit } from '@angular/core';
import { Election } from '../../../core/models/election.model';
import { ActivatedRoute, Router } from '@angular/router';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { CountValidator } from '../../../core/validators/count.validator';
import { FormUtil } from '../../../core/utils/form.util';
import { Candidate } from '../../../core/models/candidate.model';
import { CandidateService } from '../../../core/services/candidate.service';
import { finalize, take, takeUntil } from 'rxjs/operators';
import { IriUtil } from '../../../core/utils/iri.util';
import { TitleService } from '../../../core/services/title.service';
import { DirtyFormWarnableComponent } from '../../../components/form-component/dirty-form.component';
import { Subject } from 'rxjs';
import { ConfirmDialogService } from '../../../core/services/confirm-dialog.service';
import { ElectionService } from '../../../core/services/election.service';
import { NotificationService } from '../../../core/services/notification.service';
import { MatDialog } from '@angular/material/dialog';
import { ImportVoteDialogComponent } from '../../../components/election/import-vote-dialog/import-vote-dialog.component';

@Component({
    selector: 'app-vote',
    templateUrl: './vote.component.html',
    styleUrls: ['./vote.component.scss']
})
export class VoteComponent extends DirtyFormWarnableComponent implements OnInit, OnDestroy {
    public loading = false;
    public election: Election;
    public candidates: Candidate[];

    public form = this.fb.group({
        candidates: this.fb.array([], [CountValidator(1)])
    });

    private destroy$: Subject<boolean> = new Subject<boolean>();

    constructor(
        private route: ActivatedRoute,
        private router: Router,
        private fb: FormBuilder,
        private candidateService: CandidateService,
        private titleService: TitleService,
        private confirmDialogService: ConfirmDialogService,
        private electionService: ElectionService,
        private notificationService: NotificationService,
        private dialog: MatDialog
    ) {
        super();

        this.titleService.setTitle('component.votes.title1');

        this.route.data.subscribe(data => {
            this.election = data.election as Election;
            this.candidates = data.candidates as Candidate[];
            this.candidates.forEach((candidate: Candidate) => this.addVoteFormControls(candidate));
        });
    }

    ngOnInit(): void {
        this.confirmDialogService.getConfirmClickEmitter().pipe(
            takeUntil(this.destroy$)
        ).subscribe((confirm: boolean) => {
            // false = cancel, true = confirm
            if (!confirm) {
                this.save(true);
            }
        });
    }

    public save(avoidRedirect = false): void {
        if (this.form.valid) {
            this.loading = true;
            this.form.markAsPristine();

            this.candidateService.bulkUpdate((this.form.get('candidates').value)).pipe(
                take(1),
                finalize(() => this.loading = false)
            ).subscribe(() => {
                if (!avoidRedirect) {
                    void this.router.navigate([
                        'elections',
                        IriUtil.extractId(this.election['@id']),
                        'solve'
                    ]);
                }
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }

    public canLeave(): boolean {
        return !this.form.dirty;
    }

    public ngOnDestroy(): void {
        this.destroy$.next(true);
        this.destroy$.unsubscribe();
    }

    public openImportModal(): void {
        const dialog = this.dialog.open(ImportVoteDialogComponent, {
            maxWidth: 500,
            disableClose: true,
            data: {
                election: this.election
            }
        });

        dialog.beforeClosed().pipe(
            take(1)
        ).subscribe((voteImported: boolean) => {
            if (voteImported) {
                this.fetchCandidates();
                this.notificationService.success('toast.votes_successfully_imported');
            }
        });
    }

    private fetchCandidates(): void {
        this.candidateService.findByElection(IriUtil.extractId(this.election['@id'])).subscribe((candidates: Candidate[]) => {
            const candidatesArray = this.form.controls.candidates as FormArray;
            while (0 !== candidatesArray.length) {
                candidatesArray.removeAt(0);
            }

            candidates.forEach((candidate: Candidate) => this.addVoteFormControls(candidate));
        });
    }

    private addVoteFormControls(candidate: Candidate): void {
        const row = new FormGroup({
            '@id': new FormControl(candidate['@id'], []),
            name: new FormControl(candidate.name, [Validators.required]),
            numberOfVotes: new FormControl(candidate.numberOfVotes, [Validators.required, Validators.min(0)]),
        });

        const candidatesFormArray = this.form.controls.candidates as FormArray;
        candidatesFormArray.push(row);
    }
}
