import express from 'express'
const app= express()
const path= require('path')

const PORT= process.env.PORT || 3000


app.set('port', PORT)
app.set('views', path.join(__dirname, 'views'))
app.set('view engine', 'ejs')

app.use(express.urlencoded({extended: false}))


app.use(require('./routes/index.routes'))
app.use(express.static(path.join(__dirname, '/public')))

export default app