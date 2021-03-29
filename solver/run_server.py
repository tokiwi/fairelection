import asyncio
from functools import reduce
from json.decoder import JSONDecodeError

from aiohttp import web
from aiohttp.web import HTTPUnprocessableEntity, json_response

import operator
from operator import add, mul, itemgetter

from functools import reduce

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

async def handle(request):
    try:
        data = await request.json()
    except JSONDecodeError as e:
        return HTTPUnprocessableEntity(text=e.msg)
    try:
        coefficients = data['coefficients']
    except KeyError:
        return HTTPUnprocessableEntity(text="Missing data for coefficients field.")
    try:
        constraints = data['constraints']
    except KeyError:
        return HTTPUnprocessableEntity(text="Missing data for constraints field.")

    # define the problem
    prob = pl.LpProblem("voting", pl.LpMaximize)

    variables = variables_gen(len(coefficients))
    # objective function - maximize value of objects in voting
    prob += reduce(add, map(mul, coefficients, variables))

    for c in constraints:
        try:
            c_variables = [variables[cv] for cv in c['variables']]
            c_operator = operators[c['operator']]
            c_value = c['value']
            prob += c_operator(reduce(add, c_variables), c_value)
        except KeyError:
            raise

    status = prob.solve() # solve using the default solver, which is cbc
    if status != pl.LpStatusOptimal:
        return HTTPUnprocessableEntity(text=pl.LpStatus[status])

    return json_response(list(map(pl.value, variables)))

async def init(loop):
    app = web.Application(loop=loop)
    app.router.add_post('/', handle)
    srv = await loop.create_server(app.make_handler(), '127.0.0.1', 8080)
    return srv

loop = asyncio.get_event_loop()
loop.run_until_complete(init(loop))

try:
    loop.run_forever()
except KeyboardInterrupt:
    pass
