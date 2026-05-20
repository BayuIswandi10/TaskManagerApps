<template>
  <div class="desktop-root">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <div class="brand-logo">TF</div>
        <div>
          <div class="brand-name">TASKFLOW</div>
          <div class="brand-version font-mono">v1.0 — DESKTOP</div>
        </div>
      </div>

      <hr class="divider" />

      <nav class="sidebar-nav">
        <button class="btn btn-primary w-full" @click="showModal = true">+ NEW TASK</button>
      </nav>

      <!-- Today Alert -->
      <div v-if="todayTasks.length > 0" class="alert-today" style="margin-top: 24px;">
        <span>⚠</span>
        <div>
          <div style="font-weight:700;">{{ todayTasks.length }} task(s) due TODAY</div>
        </div>
      </div>

      <!-- Stats -->
      <div class="sidebar-stats">
        <div class="stat-item">
          <span class="stat-val font-mono">{{ tasks.length }}</span>
          <span class="stat-label">TOTAL</span>
        </div>
        <div class="stat-item">
          <span class="stat-val font-mono" style="color:var(--color-success)">{{ doneTasks }}</span>
          <span class="stat-label">DONE</span>
        </div>
        <div class="stat-item">
          <span class="stat-val font-mono" style="color:var(--color-warn)">{{ pendingTasks }}</span>
          <span class="stat-label">PENDING</span>
        </div>
      </div>

      <!-- User -->
      <div class="sidebar-user">
        <div class="user-info">
          <div class="user-avatar font-mono">{{ userInitial }}</div>
          <div>
            <div class="user-name">{{ auth.user?.name }}</div>
            <div class="user-email">{{ auth.user?.email }}</div>
          </div>
        </div>
        <button class="btn btn-ghost btn-sm logout-btn" @click="auth.logout()">LOGOUT</button>
      </div>
    </aside>

    <!-- Main -->
    <main class="main-area">
      <!-- Toolbar -->
      <div class="toolbar">
        <div class="toolbar-left">
          <span class="toolbar-title font-mono">ALL TASKS</span>
          <span class="task-count font-mono">{{ tasks.length }} RECORDS</span>
        </div>
        <div class="toolbar-right">
          <label class="sort-label font-mono">SORT:</label>
          <select class="sort-select" v-model="sortKey" @change="taskStore.setSort(sortKey)">
            <option value="date">Due Date</option>
            <option value="priority">Priority</option>
            <option value="description">Description</option>
          </select>
        </div>
      </div>

      <hr class="divider" />

      <!-- Task List -->
      <div class="task-area" v-if="!taskStore.loading">
        <div v-if="tasks.length === 0" class="empty-state">
          <span class="empty-icon font-mono">[ NO TASKS ]</span>
          <span>Click "+ NEW TASK" to get started</span>
        </div>
        <div v-else class="task-grid">
          <TaskCard
            v-for="task in tasks"
            :key="task.id"
            :task="task"
            :is-today="isToday(task.due_date)"
            @complete="taskStore.markComplete"
          />
        </div>
      </div>
      <div v-else class="loading-state font-mono">LOADING TASKS...</div>
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
const userInitial = computed(() => auth.user?.name?.charAt(0).toUpperCase() || 'U')

function isToday(dateStr) {
  return dateStr === new Date().toISOString().slice(0, 10)
}

async function handleAdd(data) {
  await taskStore.addTask(data)
}

onMounted(() => taskStore.fetchTasks())
</script>

<style scoped>
.desktop-root {
  display: flex;
  height: 100vh;
  overflow: hidden;
  background: var(--color-bg);
}

/* ---- Sidebar ---- */
.sidebar {
  width: 260px;
  flex-shrink: 0;
  background: var(--color-surface);
  border-right: 1px solid var(--color-border);
  display: flex;
  flex-direction: column;
  padding: 24px 20px;
  gap: 20px;
  overflow-y: auto;
}

.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 14px;
}

.brand-logo {
  width: 40px;
  height: 40px;
  background: var(--color-accent);
  color: #000;
  font-family: var(--font-mono);
  font-size: 16px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.brand-name {
  font-family: var(--font-mono);
  font-size: 16px;
  font-weight: 700;
  letter-spacing: 0.1em;
}

.brand-version {
  font-size: 10px;
  color: var(--color-text-dim);
  letter-spacing: 0.08em;
  margin-top: 2px;
}

.w-full { width: 100%; justify-content: center; }

.sidebar-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  border: 1px solid var(--color-border);
  margin-top: auto;
}

.stat-item {
  padding: 12px 8px;
  text-align: center;
  border-right: 1px solid var(--color-border);
  display: flex;
  flex-direction: column;
  gap: 4px;
}
.stat-item:last-child { border-right: none; }
.stat-val { font-size: 20px; font-weight: 700; color: var(--color-text); }
.stat-label { font-size: 9px; letter-spacing: 0.1em; color: var(--color-text-muted); }

.sidebar-user {
  border: 1px solid var(--color-border);
  padding: 12px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-avatar {
  width: 32px;
  height: 32px;
  background: var(--color-surface-2);
  border: 1px solid var(--color-border-strong);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 700;
  flex-shrink: 0;
  color: var(--color-accent);
}

.user-name { font-size: 13px; font-weight: 600; }
.user-email { font-size: 11px; color: var(--color-text-muted); }

.logout-btn { width: 100%; justify-content: center; }

/* ---- Main ---- */
.main-area {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background: var(--color-bg);
}

.toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 28px;
  gap: 16px;
  flex-shrink: 0;
}

.toolbar-left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.toolbar-title {
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.12em;
  color: var(--color-text-muted);
}

.task-count {
  font-size: 12px;
  color: var(--color-text-dim);
  letter-spacing: 0.06em;
  padding: 2px 8px;
  border: 1px solid var(--color-border);
  background: var(--color-surface);
}

.toolbar-right {
  display: flex;
  align-items: center;
  gap: 10px;
}

.sort-label {
  font-size: 11px;
  letter-spacing: 0.1em;
  color: var(--color-text-muted);
  margin: 0;
}

.sort-select {
  width: auto;
  padding: 6px 12px;
  font-family: var(--font-mono);
  font-size: 12px;
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  color: var(--color-text);
}

.task-area {
  flex: 1;
  overflow-y: auto;
  padding: 20px 28px;
}

.task-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 1px;
  border: 1px solid var(--color-border);
  background: var(--color-border);
}

.task-grid > * {
  background: var(--color-surface);
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 300px;
  gap: 12px;
  color: var(--color-text-dim);
  font-size: 13px;
}

.empty-icon {
  font-size: 24px;
  color: var(--color-text-muted);
  letter-spacing: 0.1em;
}

.loading-state {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-text-muted);
  letter-spacing: 0.1em;
  font-size: 13px;
}

.font-mono { font-family: var(--font-mono); }
</style>
