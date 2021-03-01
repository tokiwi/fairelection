export interface ResultItemResource {
    '@id': string;
    '@type': string;
    candidate: string;
    isElected: boolean;
    votes: number;
    criterias: {name: string; pictogram: string; choices: {name: string; acronym: string; isSelected: boolean}[]}[];
}
