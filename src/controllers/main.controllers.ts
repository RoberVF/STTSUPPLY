import {Request, Response} from 'express'
import Data from '../models/Data'


export async function mainPage(req:Request, res:Response, page:string){
    const teamProduct= await Data.find({"top": "True"})
    res.render(`${page}`, {
        teamProduct
    })
}

export async function page(req:Request, res:Response, page:string){
    res.render(`${page}`)
}


export async function createData(req:Request, res:Response){
    const {name, price, team, liga, year, top, type, imagePath, imagePath2, imagePath3} = req.body
    
    const newData= {
        name,
        price,
        team,
        liga,
        year,
        top,
        type,
        imagePath,
        imagePath2,
        imagePath3
    }
    const data= new Data(newData)
    await data.save()

    return res.json({
        message: "Dato guardado correctamente",
        data
    })
}

// export async function getData(req:Request, res:Response): Promise<Response>{
//     const datas= await Data.find()
//     return res.json(datas)
// }
export function getData(req:Request, res:Response){
    res.redirect("pages/incluirDatos")
}

export async function incluirDatos(req:Request, res:Response){
    res.render("pages/incluirDatos")
}

export async function verDatos(req:Request, res:Response){
    const todos= await Data.find()
    res.render("pages/verDatos", {
        todos
    })
}

export async function productCards(req:Request, res:Response, equipo:string, liga:string){
    const teamProduct= await Data.find({"team": `${equipo}`})
    const ligaConcreta= await Data.find({"ligue": `${liga}`})

    res.render(`teams/cards`, {
        ligaConcreta,
        teamProduct
    })
}

export async function productLink(req:Request, res:Response){
    const { id }= req.params
    const product= await Data.findById(id)
    res.render('teams/product', { product })
}

export async function allTypeProducts(req:Request, res:Response, type:string){
    const teamProduct= await Data.find({"type": `${type}`})
    res.render(`pages/productCardsType`, {
        teamProduct
    })
}

export async function allTypeTeamProducts(req:Request, res:Response, type:string, team:string){
    const typeProduct= await Data.find({"type": `${type}`})
    const teamProduct= await Data.find({"team": `${team}`})
    res.render(`utils/allTypeTeamNavbar`,{
        typeProduct,
        teamProduct
    })
}