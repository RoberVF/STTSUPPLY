import {connect} from 'mongoose'
import * as dotenv from 'dotenv'
import { createLogicalAnd } from 'typescript'

dotenv.config()

const {USER, PASSWORD, DATABASE}= process.env
const MONGODB= `mongodb+srv://${USER}:${PASSWORD}@products.gicdg.mongodb.net/${DATABASE}?retryWrites=true&w=majority`

export async function startConnection(){
    try{
        await connect(MONGODB,{
            useNewUrlParser: true,
            useUnifiedTopology: true,
            useFindAndModify: true
        })
        console.log("Database is connected")
    } catch(e){
        console.log(e)
        console.log("Conexion fallida")
    }
}