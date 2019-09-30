<?php

$expected     = array('$a->b->c->d->e->f->g->h->i',
                      '$a->b->c->d->e->f->g->h->i->j',
                      '$a->b->c->d->e->f->g->h->i->j->k',
                     );

$expected_not = array('$a',
                      '$a->b',
                      '$a->b->c',
                      '$a->b->c->d',
                      '$a->b->c->d->e',
                      '$a->b->c->d->e->f',
                      '$a->b->c->d->e->f->g',
                      '$a->b->c->d->e->f->g->h',
                     );

?>