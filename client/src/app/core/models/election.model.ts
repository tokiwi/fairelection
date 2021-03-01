import { ElectionCriteria } from './election-criteria.model';

export interface Election {
    '@context': string;
    '@id': string;
    '@type': string;
    name?: string;
    description?: string;
    numberOfPeopleToElect?: number;
    electionCriterias?: ElectionCriteria[];
    hasResults?: boolean;
    createdAt?: string;
    stepperPosition?: number;
}
