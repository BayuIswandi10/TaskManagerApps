import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/lib/api'

export const useTaskStore = defineStore('task', () => {
  const tasks = ref([])
  const loading = ref(false)
  const sortBy = ref('date')

  async function fetchTasks() {
    loading.value = true
    try {
      const { data } = await api.get(`/tasks?sort_by=${sortBy.value}`)
      tasks.value = data
    } catch (err) {
      console.error(err)
    } finally {
      loading.value = false
    }
  }

  async function addTask(taskData) {
    const { data } = await api.post('/tasks', taskData)
    tasks.value.push(data)
  }

  async function markComplete(id) {
    await api.patch(`/tasks/${id}/complete`)
    const task = tasks.value.find((t) => t.id === id)
    if (task) task.is_completed = true
  }

  async function deleteTask(id) {
    await api.delete(`/tasks/${id}`)
    tasks.value = tasks.value.filter((t) => t.id !== id)
  }

  function setSort(val) {
    sortBy.value = val
    fetchTasks()
  }

  const tasksToday = () => {
    const today = new Date().toISOString().slice(0, 10)
    return tasks.value.filter((t) => !t.is_completed && t.due_date === today)
  }

  return { tasks, loading, sortBy, fetchTasks, addTask, markComplete, deleteTask, setSort, tasksToday }
})
