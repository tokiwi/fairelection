import { Component, ElementRef, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { Election } from '../../../core/models/election.model';
import { ActivatedRoute, Router } from '@angular/router';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { CountValidator } from '../../../core/validators/count.validator';
import { ElectionCriteria } from '../../../core/models/election-criteria.model';
import { Criteria } from '../../../core/models/criteria.model';
import { Assignment } from '../../../core/models/assignment.model';
import { FormUtil } from '../../../core/utils/form.util';
import { IriUtil } from '../../../core/utils/iri.util';
import { CandidateResourceService } from '../../../core/services/candidate-resource.service';
import { CandidateResource } from '../../../core/models/candidate-resource.model';
import { MatDialog } from '@angular/material/dialog';
import { LoginDialogComponent } from '../../../shared/login-dialog/login-dialog.component';
import { AuthService } from '../../../core/services/auth.service';
import { TitleService } from '../../../core/services/title.service';
import { finalize, map, take, takeUntil, tap } from 'rxjs/operators';
import { DirtyFormWarnableComponent } from '../../../components/form-component/dirty-form.component';
import { ElectionService } from '../../../core/services/election.service';
import { BehaviorSubject, Subject } from 'rxjs';
import { ConfirmDialogService } from '../../../core/services/confirm-dialog.service';
import { ImportCandidateDialogComponent } from '../../../components/election/import-candidate-dialog/import-candidate-dialog.component';

@Component({
    selector: 'app-candidate',
    templateUrl: './candidate.component.html',
    styleUrls: ['./candidate.component.scss']
})
export class CandidateComponent extends DirtyFormWarnableComponent implements OnInit, OnDestroy {
    @ViewChild('fileInput', {static: false})
    public fileInput: ElementRef;

    public destroyed = new Subject<any>();
    public loading = false;
    public loadingModel = false;
    public loadingUploadModel = false;
    public election: Election;
    public displayedColumns = ['position', 'name', 'weight', 'symbol'];
    public form: FormGroup;

    private loadingSubject = new BehaviorSubject<boolean>(false);
    public loading$ = this.loadingSubject.asObservable(); // eslint-disable-line

    private destroy$: Subject<boolean> = new Subject<boolean>();

    constructor(
        private route: ActivatedRoute,
        private router: Router,
        private fb: FormBuilder,
        private candidateResourceService: CandidateResourceService,
        private dialog: MatDialog,
        private authService: AuthService,
        private titleService: TitleService,
        private electionService: ElectionService,
        private confirmDialogService: ConfirmDialogService
    ) {
        super();

        this.titleService.setTitle('component.candidates.title1');

        this.route.data.subscribe(data => {
            this.election = data.election as Election;

            this.createForm();
        });

        this.fetchCandidates();
    }

    public ngOnInit(): void {
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
            this.form.markAsPristine();

            this.authService.isAuthenticated.pipe(
                map((response, index) => [response, index]), // passes also index parameter for each next
                tap(([isAuthenticated, index]) => {
                    if (!isAuthenticated && index === 0) { // open dialog only once
                        this.openAuthenticationDialog();
                    }
                }),
                takeUntil(this.destroy$)
            ).subscribe(([isAuthenticated]) => {
                if (isAuthenticated) {
                    this.sendForm(avoidRedirect);
                }
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }

    public addCandidateRow(): void {
        this.addCandidateFormControls();
    }

    public removeCandidateRow(i: number): void {
        const items = this.form.get('candidates') as FormArray;

        if (items.length > 1) {
            items.removeAt(i);
        }
    }

    public canLeave(): boolean {
        return !this.form.dirty;
    }

    public openImportModal(): void {
        const dialog = this.dialog.open(ImportCandidateDialogComponent, {
            maxWidth: 500,
            disableClose: true,
            data: {
                election: this.election
            }
        });

        dialog.beforeClosed().pipe(
            take(1)
        ).subscribe((candidateImported: boolean) => {
            if (candidateImported) {
                this.createForm();
                this.fetchCandidates();
            }
        });
    }

    public ngOnDestroy(): void {
        this.destroy$.next(true);
        this.destroy$.unsubscribe();
    }

    private openAuthenticationDialog() {
        this.dialog.open(LoginDialogComponent, {
            minWidth: 400
        });
    }

    private fetchCandidates(): void {
        this.loadingSubject.next(true);

        this.candidateResourceService.findByElection(IriUtil.extractId(this.election['@id'])).pipe(
            finalize(() => this.loadingSubject.next(false))
        ).subscribe((res: CandidateResource) => {
            res.candidates.forEach(() => this.addCandidateFormControls());

            // add one row if there is no candidate
            if (0 === res.candidates.length) {
                this.addCandidateFormControls();
            }

            this.form.patchValue({candidates: res.candidates});
        });
    }

    private addCandidateFormControls(): void {
        const row = new FormGroup({
            '@id': new FormControl(null, []),
            name: new FormControl('', [Validators.required]),
            electionCriterias: this.fb.array([])
        });

        const electionCriteriasFormArray = row.controls.electionCriterias as FormArray;
        this.election.electionCriterias.forEach((electionCriteria: ElectionCriteria) => {
            const electionCriteriaFormGroup = new FormGroup({
                '@id': new FormControl(electionCriteria['@id']),
                name: new FormControl((electionCriteria.criteria as Criteria).name),
                pictogram: new FormControl((electionCriteria.criteria as Criteria).pictogram),
                choices: this.fb.array([]),
                selectedValue: new FormControl(null, [Validators.required]), // preselect value
            });

            const choicesFormArray = electionCriteriaFormGroup.controls.choices as FormArray;
            electionCriteria.assignments.forEach((assignment: Assignment) => {
                choicesFormArray.push(new FormGroup({
                    '@id': new FormControl(assignment['@id']),
                    name: new FormControl(assignment.item.name),
                    acronym: new FormControl(assignment.item.acronym),
                }));
            });

            electionCriteriasFormArray.push(electionCriteriaFormGroup);
        });

        const candidatesFormArray = this.form.controls.candidates as FormArray;
        candidatesFormArray.push(row);
    }

    private sendForm(avoidRedirect = false): void {
        this.loading = true;

        this.candidateResourceService.save(this.form.value).pipe(
            take(1),
            finalize(() => this.loading = false)
        ).subscribe(() => {
            if (!avoidRedirect) {
                void this.router.navigate([
                    'elections',
                    IriUtil.extractId(this.election['@id']),
                    'statistics'
                ]);
            }
        });
    }

    private createForm(): void {
        this.form = this.fb.group({
            election: [this.election['@id'], [Validators.required]],
            candidates: this.fb.array([], [CountValidator(this.election.numberOfPeopleToElect)]),
        });
    }
}
