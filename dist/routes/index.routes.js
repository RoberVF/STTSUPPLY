"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = require("express");
const router = express_1.Router();
const main_controllers_1 = require("../controllers/main.controllers");
router.route('/').get((req, res) => main_controllers_1.mainPage(req, res, "index"));
router.route('/mobile').get((req, res) => main_controllers_1.mainPage(req, res, "mobile"));
router.route('/comprar').get((req, res) => main_controllers_1.page(req, res, "pages/comprar"));
router.route('/ropa').get((req, res) => main_controllers_1.page(req, res, "pages/ropa"));
router.route('/mostrarDatos').post(main_controllers_1.createData).get(main_controllers_1.getData);
router.route('/incluir').get(main_controllers_1.incluirDatos);
router.route('/verDatos').get(main_controllers_1.verDatos);
//Lista equipos
const equipos = [
    "LIV", "CITY", "UNITED", "CHE", "ARS", "TOT",
    "BAR", "RMA", "ATM",
    "JUV", "INT", "ACM", "ROMA",
    "PSG", "MAR", "LYON",
    "DORT", "BAYERN", "LEIP"
];
//lista types
const types = [
    "camisetas", "tracksuits", "cortavientos", "pantalones", "t-shirts", "training", "portero", "retro"
];
//productCards via teams
for (let i of equipos) {
    router.route(`/${i.toString()}`).get((req, res) => main_controllers_1.productCards(req, res, i.toString(), ""));
}
//productCards via type
for (let i of types) {
    router.route(`/${i.toString()}`).get((req, res) => main_controllers_1.allTypeProducts(req, res, `${i.toString()}`));
}
//productCards via type & teams
for (let i of types) {
    for (let j of equipos) {
        router.route(`/${i.toString()}/${j.toString()}`).get((req, res) => main_controllers_1.allTypeTeamProducts(req, res, `${i.toString()}`, `${j.toString()}`));
    }
    //Manera de que cada paginaType redireccione a cada paginaTeam
}
//productLink
for (let i of equipos) {
    router.route(`/${i.toString()}/:id`).get(main_controllers_1.productLink);
}
module.exports = router;
