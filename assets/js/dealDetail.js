import './app'
import '../css/dealDetail.sass'

const btnSave = document.querySelector('#save')
const btnReport = document.getElementById('report')
const btnExpired = document.getElementById('expired')

btnReport.addEventListener('click', async e => {
  const id = e.target.dataset.id
  let res = await fetch(`/deals/report/${id}`)
  let json = await res.json()
})

btnExpired.addEventListener('click', async e => {
  const id = e.target.dataset.id
  let res = await fetch(`/deals/expired/${id}`)
  let json = await res.json()
  if(json.expired){
    window.location.href = '/'
  }
})

async function vote(dealId, isUp) {
  let vote = isUp ? 'vote-up' : 'vote-down'
  let res = await fetch(`/deals/${dealId}/${vote}`)
  let json = await res.json()
  document.querySelector('#vote-number').innerHTML = json.rating
}

async function save(id) {
  let res = await fetch(`/deals/${id}/save`)
  let json = await res.json()
  if(json.saved){
    btnSave.textContent = '☆'
  }
  else {
    btnSave.textContent = '★'
  }
}

document.querySelector('#btn-vote-up').addEventListener('click', e => {
  vote(e.currentTarget.dataset.id, true)
})

document.querySelector('#btn-vote-down').addEventListener('click', e => {
  vote(e.currentTarget.dataset.id, false)
})

if (btnSave) {
  btnSave.addEventListener('click', e => {
    save(e.currentTarget.dataset.id)
  })
}