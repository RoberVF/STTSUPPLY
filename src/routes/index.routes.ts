import {Router, Request, Response} from 'express'
const router= Router()
import {mainPage, page, getData, createData, incluirDatos, verDatos, productCards, productLink, allTypeProducts, allTypeTeamProducts} from '../controllers/main.controllers'

router.route('/').get((req, res) => mainPage(req, res, "index"))
router.route('/mobile').get((req, res) => mainPage(req, res, "mobile"))

router.route('/comprar').get((req,res) => page(req,res,"pages/comprar"))
router.route('/ropa').get((req,res) => page(req,res,"pages/ropa"))

router.route('/mostrarDatos').post(createData).get(getData)

router.route('/incluir').get(incluirDatos)

router.route('/verDatos').get(verDatos)



//Lista equipos
const equipos= [
    "LIV", "CITY", "UNITED", "CHE", "ARS", "TOT",
    "BAR", "RMA", "ATM",
    "JUV", "INT", "ACM", "ROMA",
    "PSG", "MAR", "LYON",
    "DORT", "BAYERN", "LEIP"
]

//lista types
const types= [
    "camisetas", "tracksuits", "cortavientos", "pantalones", "t-shirts", "training", "portero", "retro"
]

//productCards via teams
for(let i of equipos){
    router.route(`/${i.toString()}`).get((req, res) => productCards(req, res, i.toString(), ""))
}

//productCards via type
for(let i of types){
    router.route(`/${i.toString()}`).get((req, res) => allTypeProducts(req, res, `${i.toString()}`))
}

//productCards via type & teams
for(let i of types){
    for(let j of equipos){
        router.route(`/${i.toString()}/${j.toString()}`).get((req,res) => allTypeTeamProducts(req, res, `${i.toString()}`, `${j.toString()}`))
    }
//Manera de que cada paginaType redireccione a cada paginaTeam
}


//productLink
for(let i of equipos){
    router.route(`/${i.toString()}/:id`).get(productLink)
}



module.exports= router