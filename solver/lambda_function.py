import json
from functools import reduce
from json.decoder import JSONDecodeError
import operator
from operator import add, mul, itemgetter

import pulp as pl


operators = {
    '<': operator.lt,
    '<=': operator.le,
    '==': operator.eq,
    '!=': operator.ne,
    '>': operator.gt,
    '>=': operator.ge,
}

variables_gen = lambda count: [pl.LpVariable("x"+str(i), 0, 1, pl.LpInteger) for i in range(count)]

def lambda_handler(event, context):
    try:
        data = json.loads(event['body'])
    except JSONDecodeError as e:
        return {
            'statusCode': 422,
            'body': json.dumps(e.msg)
        }
    try:
        coefficients = data['coefficients']
    except KeyError:
        return {
            'statusCode': 422,
            'body': json.dumps('Missing data for coefficients field.')
        }
    try:
        constraints = data['constraints']
    except KeyError:
        return {
            'statusCode': 422,
            'body': json.dumps('Missing data for constraints field.')
        }

    # define the problem
    prob = pl.LpProblem("voting", pl.LpMaximize)

    variables = variables_gen(len(coefficients))
    # objective function - maximize value of objects in voting
    prob += reduce(add, map(mul, coefficients, variables))

    for c in constraints:
        try:
            c_variables = [variables[cv] for cv in c['variables']]
            if not len(c_variables):
                # empty constraint sequence
                continue
            c_operator = operators[c['operator']]
            c_value = c['value']
            prob += c_operator(reduce(add, c_variables), c_value)
        except KeyError as e:
            return {
                'statusCode': 422,
                'body': json.dumps(e.msg)
            }

    status = prob.solve() # solve using the default solver, which is cbc
    if status != pl.LpStatusOptimal:
        return {
            'statusCode': 422,
            'body': json.dumps(pl.LpStatus[status])
        }

    return {
        'statusCode': 200,
        'body': json.dumps(list(map(pl.value, variables)))
    }
