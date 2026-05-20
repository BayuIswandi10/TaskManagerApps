<template>
  <div class="tablet-root">
    <!-- Top Bar -->
    <header class="topbar">
      <div class="topbar-left">
        <div class="brand-logo font-mono">TF</div>
        <div>
          <div class="brand-name font-mono">TASKFLOW</div>
          <div class="device-label font-mono">TABLET MODE</div>
        </div>
      </div>
      <div class="topbar-right">
        <select class="sort-select font-mono" v-model="sortKey" @change="taskStore.setSort(sortKey)">
          <option value="date">SORT: DATE</option>
          <option value="priority">SORT: PRIORITY</option>
          <option value="description">SORT: DESC</option>
        </select>
        <button class="btn btn-primary" @click="showModal = true">+ ADD</button>
        <button class="btn btn-ghost btn-sm logout-btn" @click="auth.logout()">LOGOUT</button>
      </div>
    </header>

    <!-- Today Alert -->
    <div v-if="todayTasks.length > 0" class="alert-today">
      <span>⚠</span>
      <span><strong>{{ todayTasks.length }} task(s)</strong> are due today</span>
    </div>

    <!-- Stats Row -->
    <div class="stats-row">
      <div class="stat-box">
        <span class="font-mono stat-n">{{ tasks.length }}</span>
        <span class="stat-label font-mono">TOTAL</span>
      </div>
      <div class="stat-box" style="border-color: var(--color-success);">
        <span class="font-mono stat-n" style="color:var(--color-success)">{{ doneTasks }}</span>
        <span class="stat-label font-mono">DONE</span>
      </div>
      <div class="stat-box" style="border-color: var(--color-warn);">
        <span class="font-mono stat-n" style="color:var(--color-warn)">{{ pendingTasks }}</span>
        <span class="stat-label font-mono">PENDING</span>
      </div>
      <div class="stat-box" style="border-color: var(--color-danger);">
        <span class="font-mono stat-n" style="color:var(--color-danger)">{{ todayTasks.length }}</span>
        <span class="stat-label font-mono">TODAY</span>
      </div>
    </div>

    <!-- Tasks -->
    <main class="tablet-main">
      <div v-if="taskStore.loading" class="loading-state font-mono">LOADING...</div>
      <div v-else-if="tasks.length === 0" class="empty-state font-mono">
        [ NO TASKS — TAP "+ ADD" TO CREATE ONE ]
      </div>
      <div v-else class="task-list">
        <TaskCard
          v-for="task in tasks"
          :key="task.id"
          :task="task"
          :is-today="isToday(task.due_date)"
          @complete="taskStore.markComplete"
        />
      </div>
    </main>

    <AddTaskModal :show="showModal" @close="showModal = false" @submit="handleAdd" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useTaskStore } from '@/stores/task'
import TaskCard from '@/components/TaskCard.vue'
import AddTaskModal from '@/components/AddTaskModal.vue'

const auth = useAuthStore()
const taskStore = useTaskStore()
const showModal = ref(false)
const sortKey = ref('date')

const tasks = computed(() => taskStore.tasks)
const todayTasks = computed(() => taskStore.tasksToday())
const doneTasks = computed(() => tasks.value.filter(t => t.is_completed).length)
const pendingTasks = computed(() => tasks.value.filter(t => !t.is_completed).length)

function isToday(d) { return d === new Date().toISOString().slice(0, 10) }
async function handleAdd(data) { await taskStore.addTask(data) }

onMounted(() => taskStore.fetchTasks())
</script>

<style scoped>
.tablet-root {
  display: flex;
  flex-direction: column;
  height: 100vh;
  background: var(--color-bg);
  overflow: hidden;
}

.topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 24px;
  background: var(--color-surface);
  border-bottom: 2px solid var(--color-border);
  flex-shrink: 0;
  gap: 12px;
  flex-wrap: wrap;
}

.topbar-left { display: flex; align-items: center; gap: 12px; }
.topbar-right { display: flex; align-items: center; gap: 10px; }

.brand-logo {
  width: 36px;
  height: 36px;
  background: var(--color-accent);
  color: #000;
  font-size: 14px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
}

.brand-name {
  font-size: 15px;
  font-weight: 700;
  letter-spacing: 0.1em;
}

.device-label {
  font-size: 10px;
  color: var(--color-text-dim);
  letter-spacing: 0.1em;
  margin-top: 1px;
}

.sort-select {
  width: auto;
  padding: 6px 12px;
  font-size: 11px;
  letter-spacing: 0.06em;
  background: var(--color-surface-2);
  border: 1px solid var(--color-border);
  color: var(--color-text);
}

.logout-btn { white-space: nowrap; }

.stats-row {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  border-bottom: 1px solid var(--color-border);
  flex-shrink: 0;
}

.stat-box {
  padding: 12px 16px;
  border-right: 1px solid var(--color-border);
  border-left: 3px solid var(--color-border);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 4px;
}
.stat-box:last-child { border-right: none; }
.stat-n { font-size: 22px; font-weight: 700; }
.stat-label { font-size: 9px; letter-spacing: 0.12em; color: var(--color-text-muted); }

.tablet-main {
  flex: 1;
  overflow-y: auto;
  padding: 16px 24px;
}

.task-list {
  display: flex;
  flex-direction: column;
  gap: 1px;
  border: 1px solid var(--color-border);
  background: var(--color-border);
}

.task-list > * { background: var(--color-surface); }

.loading-state, .empty-state {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 200px;
  color: var(--color-text-muted);
  font-size: 13px;
  letter-spacing: 0.08em;
}

.font-mono { font-family: var(--font-mono); }
</style>
