import { Component, OnInit } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { ElectionService } from '../../../core/services/election.service';
import { FormUtil } from '../../../core/utils/form.util';
import { Router } from '@angular/router';
import { Election } from '../../../core/models/election.model';
import { IriUtil } from '../../../core/utils/iri.util';
import { TitleService } from '../../../core/services/title.service';
import { finalize, take } from 'rxjs/operators';

@Component({
    selector: 'app-create-election',
    templateUrl: './create-election.component.html',
    styleUrls: ['./create-election.component.scss']
})
export class CreateElectionComponent implements OnInit {
    public loading = false;

    public form = this.fb.group({
        name: [null, [Validators.required, Validators.maxLength(255)]],
        description: [null, [Validators.maxLength(2000)]],
    });

    constructor(
        private fb: FormBuilder,
        private electionService: ElectionService,
        private router: Router,
        private titleService: TitleService,
    ) {
        this.titleService.setTitle('component.create_election.title1');
    }

    public ngOnInit(): void {
    }

    public save(): void {
        if (this.form.valid) {
            this.loading = true;

            this.electionService.save(this.form.value).pipe(
                take(1),
                finalize(() => this.loading = false)
            ).subscribe((election: Election) => {
                void this.router.navigate(['elections', IriUtil.extractId(election['@id']), 'criterias']);
            });
        } else {
            FormUtil.validateAllFormFields(this.form);
        }
    }
}
